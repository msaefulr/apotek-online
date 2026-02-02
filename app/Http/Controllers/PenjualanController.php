<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pengiriman;

use App\Models\Obat;
use App\Models\Pelanggan;
use App\Models\MetodeBayar;
use App\Models\JenisPengiriman;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $sort = $request->query('sort', 'newest');

        $query = Penjualan::with(['pelanggan', 'metodeBayar', 'jenisPengiriman', 'pengiriman'])
            ->withCount('details');

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('status_order', 'like', "%{$q}%")
                    ->orWhere('keterangan_status', 'like', "%{$q}%")
                    ->orWhereHas('pelanggan', function ($p) use ($q) {
                        $p->where('nama_pelanggan', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('no_telp', 'like', "%{$q}%");
                    })
                    ->orWhereHas('pengiriman', function ($g) use ($q) {
                        $g->where('no_invoice', 'like', "%{$q}%")
                            ->orWhere('status_kirim', 'like', "%{$q}%");
                    });
            });
        }

        switch ($sort) {
            case 'tgl_desc':
                $query->orderBy('tgl_penjualan', 'desc');
                break;
            case 'tgl_asc':
                $query->orderBy('tgl_penjualan', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $data = $query->paginate(10)->withQueryString();

        return view('penjualan.index', compact('data', 'q', 'sort'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::orderBy('nama_pelanggan')->get();
        $metodes = MetodeBayar::orderBy('metode_pembayaran')->get();
        $jenisKirim = JenisPengiriman::orderBy('nama_ekspedisi')->get();
        $obats = Obat::orderBy('nama_obat')->get();

        return view('penjualan.create', compact('pelanggans', 'metodes', 'jenisKirim', 'obats'));
    }

    public function store(Request $request)
    {
        // filter baris item kosong (karena form bisa kirim banyak)
        $items = collect($request->input('items', []))
            ->filter(fn($it) => !empty($it['id_obat']) || !empty($it['jumlah_beli']) || !empty($it['harga_beli']))
            ->values()
            ->all();
        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'id_pelanggan' => 'required|integer',
            'id_metode_bayar' => 'required|integer',
            'id_jenis_kirim' => 'required|integer',
            'tgl_penjualan' => 'required|date',

            'url_resep' => 'nullable|string|max:255',
            'ongkos_kirim' => 'nullable|numeric|min:0',
            'biaya_app' => 'nullable|numeric|min:0',

            'status_order' => 'required|string|max:50',
            'keterangan_status' => 'nullable|string|max:255',

            'items' => 'required|array|min:1',
            'items.*.id_obat' => 'required|integer',
            'items.*.jumlah_beli' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ], [
            'items.required' => 'Minimal 1 item obat harus diinput.',
        ]);

        DB::transaction(function () use ($validated) {
            $ongkos = (float) ($validated['ongkos_kirim'] ?? 0);
            $biayaApp = (float) ($validated['biaya_app'] ?? 0);

            $totalItems = 0;

            // create penjualan
            $penjualan = Penjualan::create([
                'id_pelanggan' => $validated['id_pelanggan'],
                'id_metode_bayar' => $validated['id_metode_bayar'],
                'id_jenis_kirim' => $validated['id_jenis_kirim'],
                'tgl_penjualan' => $validated['tgl_penjualan'],

                'url_resep' => $validated['url_resep'] ?? null,
                'ongkos_kirim' => $ongkos,
                'biaya_app' => $biayaApp,

                'status_order' => $validated['status_order'],
                'keterangan_status' => $validated['keterangan_status'] ?? null,

                'total_bayar' => 0,
            ]);

            foreach ($validated['items'] as $it) {
                $idObat = (int) $it['id_obat'];
                $qty = (int) $it['jumlah_beli'];
                $harga = (float) $it['harga_beli'];
                $subtotal = $qty * $harga;

                // cek stok cukup
                $obat = Obat::lockForUpdate()->findOrFail($idObat);
                if ((int) $obat->stok < $qty) {
                    throw new \Exception("Stok obat '{$obat->nama_obat}' tidak cukup. Sisa stok: {$obat->stok}");
                }

                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id,
                    'id_obat' => $idObat,
                    'jumlah_beli' => $qty,
                    'harga_beli' => $harga,
                    'subtotal' => $subtotal,
                ]);

                // kurangi stok
                $obat->decrement('stok', $qty);

                $totalItems += $subtotal;
            }

            $grandTotal = $totalItems + $ongkos + $biayaApp;

            $penjualan->update([
                'total_bayar' => $grandTotal,
            ]);

            // auto create pengiriman (basic)
            Pengiriman::create([
                'id_penjualan' => $penjualan->id,
                'no_invoice' => 'INV-' . str_pad((string) $penjualan->id, 6, '0', STR_PAD_LEFT),
                'tgl_kirim' => null,
                'tgl_tiba' => null,
                'status_kirim' => 'Sedang Dikirim', // default sesuai enum PDM
                'nama_kurir' => null,
                'telpon_kurir' => null,
                'bukti_foto' => null,
                'keterangan' => null,
            ]);
        });

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil dibuat. Stok berkurang & pengiriman dibuat.');
    }

    public function show($id)
    {
        $penjualan = Penjualan::with([
            'pelanggan',
            'metodeBayar',
            'jenisPengiriman',
            'details.obat',
            'pengiriman'
        ])->findOrFail($id);

        $subtotal = $penjualan->details->sum('subtotal');
        $grandTotal = (float) $subtotal + (float) $penjualan->ongkos_kirim + (float) $penjualan->biaya_app;

        return view('penjualan.show', compact('penjualan', 'subtotal', 'grandTotal'));
    }

    public function destroy(Penjualan $penjualan)
    {
        // versi aman: kalau mau delete, idealnya balikin stok.
        // untuk sekarang: blok delete kalau sudah ada detail
        if ($penjualan->details()->exists()) {
            return back()->with('error', 'Penjualan tidak bisa dihapus karena ada detail. (Next: fitur retur / cancel)');
        }
        $penjualan->delete();
        return back()->with('success', 'Penjualan berhasil dihapus.');
    }
}
