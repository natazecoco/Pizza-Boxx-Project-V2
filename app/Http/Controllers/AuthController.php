<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir login.
     * Metode ini melayani login pelanggan dan pegawai.
     */
    public function showLoginForm($type = 'customer')
    {
        return view('auth.login', ['type' => $type]);
    }

    /**
     * Tangani permintaan login.
     * Metode ini memproses login untuk pelanggan dan pegawai.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $isEmployeeLogin = $request->routeIs('pegawai.login');
        $guard = $isEmployeeLogin ? 'employee' : 'web';

        // Coba otentikasi dengan guard yang sesuai
        if (Auth::guard($guard)->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard($guard)->user();
            $request->session()->regenerate();

            if ($isEmployeeLogin) {
                // Jika login sebagai pegawai, periksa apakah pengguna memiliki peran 'admin' atau 'employee'
                if ($user->hasAnyRole(['admin', 'employee'])) {
                    return redirect()->route('pegawai.dashboard');
                }
                
                // Jika tidak memiliki peran yang sesuai, logout paksa
                Auth::guard($guard)->logout();
                return back()->withInput()->withErrors(['email' => 'Akses terbatas untuk pegawai.']);
            }
            
            // Jika login sebagai pelanggan, pastikan pengguna TIDAK memiliki peran admin/pegawai
            if (!$user->hasAnyRole(['admin', 'employee'])) {
                return redirect()->intended('/');
            } 
            
            // Jika pengguna memiliki peran admin/pegawai, logout paksa
            Auth::guard($guard)->logout();
            return back()->withInput()->withErrors(['email' => 'Akun ini adalah akun pegawai. Silakan gunakan portal pegawai.']);
        }
        
        // Autentikasi gagal
        $message = 'Email atau password salah.';
        return back()->withInput()->withErrors(['email' => $message]);
    }
    
    /**
     * Tampilkan formulir pendaftaran.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Tangani permintaan pendaftaran.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // --- Otomatis menetapkan peran 'customer' kepada pengguna baru ---
        $user->assignRole('customer');
        
        Auth::login($user);
        return redirect('/');
    }

    /**
     * Tangani permintaan logout pelanggan.
     */
    public function logout(Request $request)
    {
        // Logout dari guard 'web'
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Tangani permintaan logout pegawai/admin.
     */
    public function employeeLogout(Request $request)
    {
        // Logout dari guard 'employee'
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('pegawai.login');
    }
}