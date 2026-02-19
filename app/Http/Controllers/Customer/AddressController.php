<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Address;

class AddressController extends Controller
{
public function create()
    {
        return view('pages.customer.address-create');
    }

    private function validateAddress(Request $request)
    {
        return $request->validate([
            'receiver_name'  => 'nullable|string|max:255', 
            'label'          => 'required|string|max:50',
            'phone'          => 'required|string|max:20',
            'map_address'    => 'required|string',         // Wajibkan dari Peta
            'address'        => 'nullable|string',         
            'detail_address' => 'required|string',
            'latitude'       => 'required',
            'longitude'      => 'required',
            'city'           => 'nullable|string|max:100',
            'province'       => 'nullable|string|max:100',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateAddress($request);

        try {
            $user = Auth::user();
            
            // Gabungkan alamat untuk kolom 'address' utama
            // Kita prioritaskan detail_address lalu map_address
            $fullAddress = $validated['detail_address'] . ', ' . $validated['map_address'];

            $address = $user->addresses()->create([
                'receiver_name'  => $validated['receiver_name'] ?? $user->name,
                'label'          => $validated['label'],
                'phone'          => $validated['phone'],
                'map_address'    => $validated['map_address'],
                'detail_address' => $validated['detail_address'],
                'address'        => $fullAddress, 
                'latitude'       => $validated['latitude'],
                'longitude'      => $validated['longitude'],
                'city'           => $validated['city'],
                'province'       => $validated['province'] ?? '',
                'is_primary'     => $user->addresses()->count() === 0,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Alamat baru berhasil ditambahkan!',
                    'address' => $address // GANTI DARI 'data' KE 'address' AGAR COCOK DENGAN JS
                ]);
            }

            return redirect()->route('user.profile')->with('success', 'Alamat ditambahkan.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi
        $validated = $this->validateAddress($request);

        // 2. Cari data milik user (Security Check)
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        
        try {
            // 3. Update data
            // Jangan lupa update gabungan alamat juga
            $fullAddress = $validated['detail_address'] . ', ' . $validated['map_address'];

            $address->update([
                'receiver_name'  => $validated['receiver_name'],
                'label'          => $validated['label'],
                'phone'          => $validated['phone'],
                'map_address'    => $validated['map_address'],
                'detail_address' => $validated['detail_address'],
                'address'        => $fullAddress, // Update legacy column
                'latitude'       => $validated['latitude'],
                'longitude'      => $validated['longitude'],
                'city'           => $validated['city'] ?? $address->city,
                'province'       => $validated['province'] ?? $address->province,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Alamat berhasil diperbarui!',
                    'data'    => $address
                ]);
            }

            // Redirect pakai fragment hash biar balik ke tab alamat
            return redirect(route('user.profile') . '#address')
                            ->with('success', 'Alamat berhasil diperbarui!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal update data: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Gagal update data.');
        }
    }

    public function delete(Request $request, Address $address)
    {
        // Security Check
        if ($address->user_id !== Auth::id()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
            }
            return redirect()->route('user.profile')->with('error', 'Akses ditolak.');
        }

        try {
            $address->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Alamat berhasil dihapus.'
                ]);
            }

            return redirect(route('user.profile') . '#address')
                            ->with('success', 'Alamat berhasil dihapus.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Gagal menghapus data.'], 500);
            }
            return back()->with('error', 'Gagal menghapus data.');
        }
    }

    public function setPrimary($id)
    {
        $userId = Auth::id();
        // Cek dulu apakah alamat itu benar milik user
        $targetAddress = Address::where('user_id', $userId)->where('id', $id)->firstOrFail();

        try {
            DB::transaction(function () use ($userId, $id) {
                // Set semua alamat user ini jadi bukan primary
                Address::where('user_id', $userId)->update(['is_primary' => false]);
                
                // Set alamat target jadi primary
                Address::where('user_id', $userId)->where('id', $id)->update(['is_primary' => true]);
            });

            return redirect(route('user.profile') . '#address')
                    ->with('success', 'Alamat utama berhasil diperbarui!');
                            
        } catch (\Exception $e) {
            return redirect(route('user.profile') . '#address')
                    ->with('error', 'Gagal memperbarui alamat utama.');
        }
    }
}