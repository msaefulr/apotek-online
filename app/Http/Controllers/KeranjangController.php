<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Obat;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pengiriman;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    public function index(Request $request)
    {
        $pelangganId = $request->query('id_pelanggan');
        $q = $request->query('q');

        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        $obats = Obat::orderBy('nama_obat')->get();

        $itemsQuery = Keranjang::with(['pelanggan', 'obat']);

        if ($pelangganId) {
            $itemsQuery->where('id_pelanggan', $pelangganId);
        }

        if ($q) {
            $itemsQuery->where(function ($w) use ($q) {
                $w->whereHas('obat', function ($o) use ($q) {
                    $o->where('nama_obat', 'like', "%{$q}%");
                })->orWhereHas('pelanggan', function ($p) use ($q) {
                    $p->where('nama_pelanggan', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('no_telp', 'like', "%{$q}%");
                });
            });
        }

        $items = $itemsQuery->latest()->paginate(10)->withQueryString();

        // total subtotal untuk pelanggan terpilih
        $total = 0;
        if ($pelangganId) {
            $total = Keranjang::where('id_pelanggan', $pelangganId)
                ->get()
                ->sum(fn($r) => (float) $r->harga * (float) $r->jumlah_order);
        }

        return view('keranjang.index', compact('items', 'pelanggans', 'obats', 'pelangganId', 'q', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|integer',
            'id_obat' => 'required|integer',
            'jumlah_order' => 'required|numeric|min:1',
        ]);

        $obat = Obat::findOrFail($validated['id_obat']);

        // pakai harga_jual obat sebagai default
        $harga = (float) ($obat->harga_jual ?? 0);

        // kalau item obat sudah ada di keranjang pelanggan → tambah qty (bukan bikin row baru)
        $row = Keranjang::where('id_pelanggan', $validated['id_pelanggan'])
            ->where('id_obat', $validated['id_obat'])
            ->first();

        if ($row) {
            $row->update([
                'jumlah_order' => (float) $row->jumlah_order + (float) $validated['jumlah_order'],
                'harga' => $harga, // keep latest price
            ]);
        } else {
            Keranjang::create([
                'id_pelanggan' => $validated['id_pelanggan'],
                'id_obat' => $validated['id_obat'],
                'jumlah_order' => (float) $validated['jumlah_order'],
                'harga' => $harga,
            ]);
        }

        return back()->with('success', 'Item masuk keranjang ✅');
    }

    public function update(Request $request, Keranjang $keranjang)
    {
        $validated = $request->validate([
            'jumlah_order' => 'required|numeric|min:1',
        ]);

        $keranjang->update([
            'jumlah_order' => (float) $validated['jumlah_order'],
        ]);

        return back()->with('success', 'Qty keranjang diupdate ✅');
    }

    public function destroy(Keranjang $keranjang)
    {
        $keranjang->delete();
        return back()->with('success', 'Item keranjang dihapus ✅');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan' => 'required|integer',
            'id_metode_bayar' => 'required|integer',
            'id_jenis_kirim' => 'required|integer',
            'ongkos_kirim' => 'nullable|numeric|min:0',
            'biaya_app' => 'nullable|numeric|min:0',
        ]);

        $pelangganId = (int) $validated['id_pelanggan'];

        $cartItems = Keranjang::with('obat')
            ->where('id_pelanggan', $pelangganId)
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang masih kosong.');
        }

        try {
            DB::transaction(function () use ($validated, $cartItems, $pelangganId) {

                $ongkos = (float) ($validated['ongkos_kirim'] ?? 0);
                $biayaApp = (float) ($validated['biaya_app'] ?? 0);

                $penjualan = Penjualan::create([
                    'id_pelanggan' => $pelangganId,
                    'id_metode_bayar' => (int) $validated['id_metode_bayar'],
                    'id_jenis_kirim' => (int) $validated['id_jenis_kirim'],
                    'tgl_penjualan' => now()->toDateString(),

                    'url_resep' => null,
                    'ongkos_kirim' => $ongkos,
                    'biaya_app' => $biayaApp,

                    'status_order' => 'Menunggu Konfirmasi',
                    'keterangan_status' => 'Checkout dari keranjang',
                    'total_bayar' => 0,
                ]);

                $totalItems = 0;

                foreach ($cartItems as $item) {
                    $obat = Obat::lockForUpdate()->findOrFail($item->id_obat);

                    $qty = (int) $item->jumlah_order;
                    $harga = (float) $item->harga;
                    $subtotal = $qty * $harga;

                    if ((int) $obat->stok < $qty) {
                        throw new \Exception("Stok obat '{$obat->nama_obat}' tidak cukup. Sisa stok: {$obat->stok}");
                    }

                    DetailPenjualan::create([
                        'id_penjualan' => $penjualan->id,
                        'id_obat' => $item->id_obat,
                        'jumlah_beli' => $qty,
                        'harga_beli' => $harga,
                        'subtotal' => $subtotal,
                    ]);

                    $obat->decrement('stok', $qty);

                    $totalItems += $subtotal;
                }

                $grandTotal = $totalItems + $ongkos + $biayaApp;

                $penjualan->update([
                    'total_bayar' => $grandTotal,
                ]);

                Pengiriman::create([
                    'id_penjualan' => $penjualan->id,
                    'no_invoice' => 'INV-' . str_pad((string) $penjualan->id, 6, '0', STR_PAD_LEFT),
                    'tgl_kirim' => null,
                    'tgl_tiba' => null,
                    'status_kirim' => 'Sedang Dikirim',
                    'nama_kurir' => null,
                    'telpon_kurir' => null,
                    'bukti_foto' => null,
                    'keterangan' => null,
                ]);

                // clear keranjang pelanggan
                Keranjang::where('id_pelanggan', $pelangganId)->delete();
            });

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('penjualan.index')->with('success', 'Checkout sukses ✅ Penjualan dibuat & keranjang dikosongkan.');
    }
}
