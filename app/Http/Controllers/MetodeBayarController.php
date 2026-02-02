<?php

namespace App\Http\Controllers;

use App\Models\MetodeBayar;
use Illuminate\Http\Request;

class MetodeBayarController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $sort = $request->query('sort', 'newest');

        $query = MetodeBayar::query();

        if ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('metode_pembayaran', 'like', "%{$q}%")
                    ->orWhere('tempat_bayar', 'like', "%{$q}%")
                    ->orWhere('no_rekening', 'like', "%{$q}%");
            });
        }

        switch ($sort) {
            case 'nama_asc':
                $query->orderBy('metode_pembayaran', 'asc');
                break;
            case 'nama_desc':
                $query->orderBy('metode_pembayaran', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $data = $query->paginate(10)->withQueryString();

        return view('metode_bayar.index', compact('data', 'q', 'sort'));
    }

    public function create()
    {
        return view('metode_bayar.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metode_pembayaran' => 'required|string|max:30',
            'tempat_bayar' => 'nullable|string|max:50',
            'no_rekening' => 'nullable|string|max:25',
            'url_logo' => 'nullable|string|max:255',
        ]);

        MetodeBayar::create($validated);

        return redirect()->route('metode-bayar.index')
            ->with('success', 'Metode bayar berhasil ditambahkan.');
    }

    public function edit(MetodeBayar $metodeBayar)
    {
        return view('metode_bayar.edit', ['item' => $metodeBayar]);
    }

    public function update(Request $request, MetodeBayar $metodeBayar)
    {
        $validated = $request->validate([
            'metode_pembayaran' => 'required|string|max:30',
            'tempat_bayar' => 'nullable|string|max:50',
            'no_rekening' => 'nullable|string|max:25',
            'url_logo' => 'nullable|string|max:255',
        ]);

        $metodeBayar->update($validated);

        return redirect()->route('metode-bayar.index')
            ->with('success', 'Metode bayar berhasil diupdate.');
    }

    public function destroy(MetodeBayar $metodeBayar)
    {
        $metodeBayar->delete();
        return back()->with('success', 'Metode bayar berhasil dihapus.');
    }
}
