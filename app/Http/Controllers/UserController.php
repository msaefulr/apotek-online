<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = User::query();

        if ($q) {
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('jabatan', 'like', "%{$q}%");
        }

        $data = $query->latest()->paginate(10)->withQueryString();

        return view('users.index', compact('data', 'q'));
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'apoteker', 'karyawan', 'kasir', 'pemilik'];
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'required|string|in:admin,apoteker,karyawan,kasir,pemilik',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate ✅');
    }
}
