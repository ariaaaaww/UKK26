<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView()
    {
        $title = 'Masuk Akunm';
        return view('auth.login', [
            'title' => $title,
        ]);
    }

    public function loginPost(Request $request)
    {
        // Validasi Request
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Cek kredensial pengguna menggunakan metode Auth::attempt(). Jika kredensial valid, maka pengguna akan diarahkan ke halaman dashboard. Jika tidak, maka akan dikembalikan ke halaman login dengan pesan error.
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->role) {
                'Supervisor' => redirect()->route('dealer.index'),
                'Dealer' => redirect()->route('task-submissions.index'),
                'Admin' => redirect()->route('admin.users.index'),
                // default => redirect()->intended('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function registerView()
    {
        $title = 'Daftar Akun - LMS Astra Report';
        return view('auth.register',[
            'title' => $title,
        ]);
    }

    public function registerPost(Request $request)
    {
        // Validasi Request
        $validatedRequest = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Mengirimkan data ke database menggunakan model User. Data yang dikirimkan adalah nama, email, password (yang di-hash menggunakan bcrypt), dan role (yang di-set sebagai 'dealer').
        User::create([
            'name' => $validatedRequest['name'],
            'email' => $validatedRequest['email'],
            'password' => bcrypt($validatedRequest['password']),
            'role' => 'dealer',
        ]);

        // Handle success response
        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Menghapus invalidate dan meregenerasi token sesi untuk mencegah serangan CSRF (Cross-Site Request Forgery) setelah pengguna logout.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
