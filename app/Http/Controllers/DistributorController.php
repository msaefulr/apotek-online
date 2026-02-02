<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $sort = $request->query('sort', 'newest');

        $query = Distributor::query();

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama_distributor', 'like', "%{$q}%")
                    ->orWhere('telepon', 'like', "%{$q}%")
                    ->orWhere('alamat', 'like', "%{$q}%");
            });
        }

        switch ($sort) {
            case 'nama_asc':
                $query->orderBy('nama_distributor', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('nama_distributor', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $data = $query->paginate(10)->withQueryString();

        return view('distributor.index', compact('data', 'q', 'sort'));
    }

    public function create()
    {
        return view('distributor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_distributor' => 'required|string|max:50',
            'telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:255',
        ]);

        Distributor::create($validated);

        return redirect()->route('distributor.index')->with('success', 'Distributor berhasil ditambahkan.');
    }

    public function edit(Distributor $distributor)
    {
        return view('distributor.edit', ['item' => $distributor]);
    }

    public function update(Request $request, Distributor $distributor)
    {
        $validated = $request->validate([
            'nama_distributor' => 'required|string|max:50',
            'telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:255',
        ]);

        $distributor->update($validated);

        return redirect()->route('distributor.index')->with('success', 'Distributor berhasil diupdate.');
    }

    public function destroy(Distributor $distributor)
    {
        $distributor->delete();
        return back()->with('success', 'Distributor berhasil dihapus.');
    }
}
