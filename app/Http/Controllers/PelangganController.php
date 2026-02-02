<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $sort = $request->query('sort', 'newest');

        $query = Pelanggan::query();

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama_pelanggan', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('no_telp', 'like', "%{$q}%")
                    ->orWhere('kota1', 'like', "%{$q}%")
                    ->orWhere('provinsi1', 'like', "%{$q}%");
            });
        }

        switch ($sort) {
            case 'nama_asc':
                $query->orderBy('nama_pelanggan', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_pelanggan', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $data = $query->paginate(10)->withQueryString();

        return view('pelanggan.index', compact('data', 'q', 'sort'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'katakunci' => 'nullable|string|max:15',
            'no_telp' => 'nullable|string|max:15',

            'alamat1' => 'nullable|string|max:255',
            'kota1' => 'nullable|string|max:255',
            'provinsi1' => 'nullable|string|max:255',
            'kodepos1' => 'nullable|string|max:255',

            'alamat2' => 'nullable|string|max:255',
            'kota2' => 'nullable|string|max:255',
            'provinsi2' => 'nullable|string|max:255',
            'kodepos2' => 'nullable|string|max:255',

            'alamat3' => 'nullable|string|max:255',
            'kota3' => 'nullable|string|max:255',
            'provinsi3' => 'nullable|string|max:255',
            'kodepos3' => 'nullable|string|max:255',

            'url_ktp' => 'nullable|string|max:255',
            'url_foto' => 'nullable|string|max:255',
        ]);

        Pelanggan::create($validated);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', ['item' => $pelanggan]);
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'katakunci' => 'nullable|string|max:15',
            'no_telp' => 'nullable|string|max:15',

            'alamat1' => 'nullable|string|max:255',
            'kota1' => 'nullable|string|max:255',
            'provinsi1' => 'nullable|string|max:255',
            'kodepos1' => 'nullable|string|max:255',

            'alamat2' => 'nullable|string|max:255',
            'kota2' => 'nullable|string|max:255',
            'provinsi2' => 'nullable|string|max:255',
            'kodepos2' => 'nullable|string|max:255',

            'alamat3' => 'nullable|string|max:255',
            'kota3' => 'nullable|string|max:255',
            'provinsi3' => 'nullable|string|max:255',
            'kodepos3' => 'nullable|string|max:255',

            'url_ktp' => 'nullable|string|max:255',
            'url_foto' => 'nullable|string|max:255',
        ]);

        $pelanggan->update($validated);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diupdate.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus.');
    }
}
