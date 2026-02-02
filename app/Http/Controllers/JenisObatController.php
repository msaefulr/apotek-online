<?php

namespace App\Http\Controllers;

use App\Models\JenisObat;
use Illuminate\Http\Request;

class JenisObatController extends Controller
{
    /**
     * Display a listing of the resource.
     * PRO MODE: search + sort + withCount(obats) + pagination keep query
     */
    public function index(Request $request)
    {
        $q = $request->query('q');
        $sort = $request->query('sort', 'newest');

        $query = JenisObat::query()->withCount('obats');

        // SEARCH
        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('jenis', 'like', "%{$q}%")
                    ->orWhere('deskripsi_jenis', 'like', "%{$q}%");
            });
        }

        // SORT
        switch ($sort) {
            case 'nama_asc':
                $query->orderBy('jenis', 'asc');
                break;

            case 'nama_desc':
                $query->orderBy('jenis', 'desc');
                break;

            case 'obat_desc':
                $query->orderBy('obats_count', 'desc');
                break;

            case 'obat_asc':
                $query->orderBy('obats_count', 'asc');
                break;

            default:
                $query->latest();
                break;
        }

        $data = $query->paginate(10)->withQueryString();

        return view('jenis_obat.index', compact('data', 'q', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_obat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis' => 'required|string|max:50',
            'deskripsi_jenis' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
        ]);

        JenisObat::create($validated);

        return redirect()
            ->route('jenis-obat.index')
            ->with('success', 'Jenis obat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * (Optional, not used by default resource UI)
     */
    public function show(JenisObat $jenisObat)
    {
        // Kalau belum butuh detail page, redirect ke edit:
        return redirect()->route('jenis-obat.edit', $jenisObat->id);
        // Atau bikin view 'jenis_obat.show' kalau mau halaman detail.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisObat $jenisObat)
    {
        return view('jenis_obat.edit', ['item' => $jenisObat]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisObat $jenisObat)
    {
        $validated = $request->validate([
            'jenis' => 'required|string|max:50',
            'deskripsi_jenis' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255',
        ]);

        $jenisObat->update($validated);

        return redirect()
            ->route('jenis-obat.index')
            ->with('success', 'Jenis obat berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisObat $jenisObat)
    {
        // Optional safety: jangan hapus kalau masih ada obat
        // if ($jenisObat->obats()->exists()) {
        //     return back()->with('success', 'Tidak bisa hapus: masih ada obat di jenis ini.');
        // }

        $jenisObat->delete();

        return back()->with('success', 'Jenis obat berhasil dihapus.');
    }
}
