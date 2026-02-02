<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use Illuminate\Http\Request;

class PengirimanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $sort = $request->query('sort', 'newest');

        $query = Pengiriman::with(['penjualan.pelanggan', 'penjualan.jenisPengiriman']);

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('no_invoice', 'like', "%{$q}%")
                    ->orWhere('status_kirim', 'like', "%{$q}%")
                    ->orWhere('nama_kurir', 'like', "%{$q}%")
                    ->orWhereHas('penjualan.pelanggan', function ($p) use ($q) {
                        $p->where('nama_pelanggan', 'like', "%{$q}%")
                            ->orWhere('no_telp', 'like', "%{$q}%");
                    });
            });
        }

        switch ($sort) {
            case 'kirim_desc':
                $query->orderBy('tgl_kirim', 'desc');
                break;
            case 'kirim_asc':
                $query->orderBy('tgl_kirim', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $data = $query->paginate(10)->withQueryString();

        return view('pengiriman.index', compact('data', 'q', 'sort'));
    }

    public function edit(Pengiriman $pengiriman)
    {
        $pengiriman->load(['penjualan.pelanggan', 'penjualan.jenisPengiriman']);
        return view('pengiriman.edit', ['item' => $pengiriman]);
    }

    public function update(Request $request, Pengiriman $pengiriman)
    {
        $validated = $request->validate([
            'status_kirim' => 'required|string|max:50',
            'tgl_kirim' => 'nullable|date',
            'tgl_tiba' => 'nullable|date|after_or_equal:tgl_kirim',

            'nama_kurir' => 'nullable|string|max:100',
            'telpon_kurir' => 'nullable|string|max:20',
            'bukti_foto' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if (($validated['status_kirim'] ?? null) === 'Tiba Di Tujuan' && empty($validated['tgl_tiba'])) {
            $validated['tgl_tiba'] = now()->toDateString();
        }
        if (($validated['status_kirim'] ?? null) === 'Sedang Dikirim' && empty($validated['tgl_kirim'])) {
            $validated['tgl_kirim'] = now()->toDateString();
        }

        $pengiriman->update($validated);

        return redirect()->route('pengiriman.index')->with('success', 'Pengiriman berhasil diupdate.');
    }

    public function markArrived(Pengiriman $pengiriman)
    {
        $pengiriman->update([
            'status_kirim' => 'Tiba Di Tujuan',
            'tgl_kirim' => $pengiriman->tgl_kirim ?: now()->toDateString(),
            'tgl_tiba' => $pengiriman->tgl_tiba ?: now()->toDateString(),
        ]);

        return back()->with('success', 'Pengiriman ditandai Tiba Di Tujuan ✅');
    }

}
