<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function show()
    {
        $addresses = Auth::user()->addresses ?? collect();
        // Change the view path from 'user.profile' to 'pages.customer.profile'
        return view('pages.customer.profile', compact('addresses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $user->name = $request->name;
        $user->save();
        return back()->with('success', 'Profile berhasil diperbarui.');
    }
}