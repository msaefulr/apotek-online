<?php

namespace App\Http\Controllers;

use App\Models\JenisPengiriman;
use Illuminate\Http\Request;

class JenisPengirimanController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $sort = $request->query('sort', 'newest');

        $query = JenisPengiriman::query();

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama_ekspedisi', 'like', "%{$q}%")
                    ->orWhere('jenis_kirim', 'like', "%{$q}%");
            });
        }

        switch ($sort) {
            case 'nama_asc':
                $query->orderBy('nama_ekspedisi', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_ekspedisi', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $data = $query->paginate(10)->withQueryString();

        return view('jenis_pengiriman.index', compact('data', 'q', 'sort'));
    }

    public function create()
    {
        return view('jenis_pengiriman.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_kirim' => 'required|string|max:50', // enum di DB, validasi string aman
            'nama_ekspedisi' => 'required|string|max:255',
            'logo_ekspedisi' => 'nullable|string|max:255',
        ]);

        JenisPengiriman::create($validated);

        return redirect()->route('jenis-pengiriman.index')
            ->with('success', 'Jenis pengiriman berhasil ditambahkan.');
    }

    public function edit(JenisPengiriman $jenisPengiriman)
    {
        return view('jenis_pengiriman.edit', ['item' => $jenisPengiriman]);
    }

    public function update(Request $request, JenisPengiriman $jenisPengiriman)
    {
        $validated = $request->validate([
            'jenis_kirim' => 'required|string|max:50',
            'nama_ekspedisi' => 'required|string|max:255',
            'logo_ekspedisi' => 'nullable|string|max:255',
        ]);

        $jenisPengiriman->update($validated);

        return redirect()->route('jenis-pengiriman.index')
            ->with('success', 'Jenis pengiriman berhasil diupdate.');
    }

    public function destroy(JenisPengiriman $jenisPengiriman)
    {
        $jenisPengiriman->delete();
        return back()->with('success', 'Jenis pengiriman berhasil dihapus.');
    }
}
