<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Distributor;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $sort = $request->query('sort', 'newest');

        $query = Pembelian::with('distributor')->withCount('details');

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nota', 'like', "%{$q}%")
                    ->orWhereHas('distributor', function ($d) use ($q) {
                        $d->where('nama_distributor', 'like', "%{$q}%");
                    });
            });
        }

        switch ($sort) {
            case 'tgl_asc':
                $query->orderBy('tgl_pembelian', 'asc');
                break;
            case 'tgl_desc':
                $query->orderBy('tgl_pembelian', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $data = $query->paginate(10)->withQueryString();

        return view('pembelian.index', compact('data', 'q', 'sort'));
    }

    public function create()
    {
        $distributors = Distributor::orderBy('nama_distributor')->get();
        $obats = Obat::with('jenisObat')->orderBy('nama_obat')->get(); // kalau relasi ada
        return view('pembelian.create', compact('distributors', 'obats'));
    }

    public function store(Request $request)
    {
        $items = collect($request->input('items', []))
            ->filter(fn($it) => !empty($it['id_obat']) || !empty($it['jumlah_beli']) || !empty($it['harga_beli']))
            ->values()
            ->all();

        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'nota' => 'required|string|max:100',
            'tgl_pembelian' => 'required|date',
            'id_distributor' => 'required|integer',

            'items' => 'required|array|min:1',
            'items.*.id_obat' => 'required|integer',
            'items.*.jumlah_beli' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ], [
            'items.required' => 'Minimal 1 item obat harus diinput.',
        ]);

        DB::transaction(function () use ($validated) {
            $total = 0;

            // create pembelian
            $pembelian = Pembelian::create([
                'nota' => $validated['nota'],
                'tgl_pembelian' => $validated['tgl_pembelian'],
                'id_distributor' => $validated['id_distributor'],
                'total_bayar' => 0, // update setelah hitung total
            ]);

            foreach ($validated['items'] as $it) {
                $subtotal = (int) $it['jumlah_beli'] * (float) $it['harga_beli'];
                $total += $subtotal;

                DetailPembelian::create([
                    'id_pembelian' => $pembelian->id,
                    'id_obat' => $it['id_obat'],
                    'jumlah_beli' => $it['jumlah_beli'],
                    'harga_beli' => $it['harga_beli'],
                    'subtotal' => $subtotal,
                ]);

                // update stok obat (tambah)
                Obat::where('id', $it['id_obat'])->increment('stok', (int) $it['jumlah_beli']);
            }

            $pembelian->update(['total_bayar' => $total]);
        });

        return redirect()->route('pembelian.index')->with('success', 'Pembelian berhasil disimpan & stok bertambah.');
    }

    public function show($id)
    {
        $pembelian = Pembelian::with(['distributor', 'details.obat'])
            ->findOrFail($id);

        $subtotal = $pembelian->details->sum('subtotal');

        return view('pembelian.show', compact('pembelian', 'subtotal'));
    }

    public function destroy(Pembelian $pembelian)
    {
        // NOTE:
        // kalau hapus pembelian, idealnya stok dikurangi balik.
        // tapi karena kamu bilang "pembelian dulu", ini opsi aman: tolak delete kalau sudah ada detail.
        // (bisa kamu ubah kalau mau).
        if ($pembelian->details()->exists()) {
            return back()->with('error', 'Pembelian tidak bisa dihapus karena memiliki detail. Hapus detail dulu (fitur next).');
        }

        $pembelian->delete();
        return back()->with('success', 'Pembelian berhasil dihapus.');
    }
}
