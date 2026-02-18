<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        // Ambil user dan alamatnya
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_primary', 'desc')->get();
        
        return view('pages.customer.profile', compact('user', 'addresses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'birth_date'   => 'nullable|date',
            'phone_number' => 'required|string|max:20',
        ]);

        // 1. Simpan data ke kolom-kolom baru
        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        $user->birth_date   = $request->birth_date;
        $user->phone_number = $request->phone_number;

        // 2. Update kolom 'name' bawaan Laravel (Gabungan)
        // Ini penting supaya fitur lain yang pakai $user->name tidak error/kosong
        $user->name = $request->first_name . ' ' . $request->last_name;

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}