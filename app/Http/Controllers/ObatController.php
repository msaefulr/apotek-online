<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\JenisObat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     * PRO MODE: search + filter jenis + sort + pagination keep query
     */
    public function index(Request $request)
    {
        $q = $request->query('q');
        $jenisId = $request->query('jenis');
        $sort = $request->query('sort', 'newest');

        $query = Obat::with('jenis');

        // SEARCH
        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama_obat', 'like', "%{$q}%")
                    ->orWhere('harga_jual', 'like', "%{$q}%");
            });
        }

        // FILTER JENIS
        if ($jenisId) {
            $query->where('idjenis', $jenisId);
        }

        // SORTING
        switch ($sort) {
            case 'nama_asc':
                $query->orderBy('nama_obat', 'asc');
                break;

            case 'nama_desc':
                $query->orderBy('nama_obat', 'desc');
                break;

            case 'harga_asc':
                $query->orderBy('harga_jual', 'asc');
                break;

            case 'harga_desc':
                $query->orderBy('harga_jual', 'desc');
                break;

            case 'stok_asc':
                $query->orderBy('stok', 'asc');
                break;

            case 'stok_desc':
                $query->orderBy('stok', 'desc');
                break;

            default:
                $query->latest();
                break;
        }

        $data = $query->paginate(10)->withQueryString();
        $jenis = JenisObat::orderBy('jenis')->get();

        return view('obat.index', compact('data', 'jenis', 'q', 'jenisId', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenis = JenisObat::orderBy('jenis')->get();
        return view('obat.create', compact('jenis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:100',
            'idjenis' => 'required|exists:jenis_obat,id',
            'harga_jual' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi_obat' => 'nullable|string',
            'foto1' => 'nullable|string|max:255',
            'foto2' => 'nullable|string|max:255',
            'foto3' => 'nullable|string|max:255',
        ]);

        Obat::create($validated);

        return redirect()
            ->route('obat.index')
            ->with('success', 'Obat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     * (Optional, not used by default resource UI)
     */
    public function show(Obat $obat)
    {
        // Kalau kamu belum butuh detail page, bisa redirect saja:
        return redirect()->route('obat.edit', $obat->id);
        // Atau bikin view 'obat.show' kalau mau halaman detail.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Obat $obat)
    {
        $jenis = JenisObat::orderBy('jenis')->get();
        return view('obat.edit', compact('obat', 'jenis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Obat $obat)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:100',
            'idjenis' => 'required|exists:jenis_obat,id',
            'harga_jual' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',
            'deskripsi_obat' => 'nullable|string',
            'foto1' => 'nullable|string|max:255',
            'foto2' => 'nullable|string|max:255',
            'foto3' => 'nullable|string|max:255',
        ]);

        $obat->update($validated);

        return redirect()
            ->route('obat.index')
            ->with('success', 'Obat berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Obat $obat)
    {
        $obat->delete();

        return back()->with('success', 'Obat berhasil dihapus.');
    }
}
