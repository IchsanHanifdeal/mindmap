<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Email harus berupa alamat email yang valid.',
            'email.unique' => 'Email sudah digunakan, silakan gunakan email lain.',
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput()
                ->with('toast', [
                    'message' => 'Validasi gagal. Mohon perbaiki kesalahan dan coba lagi.',
                    'type' => 'error'
                ]);
        }

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'user';

            $user->save();
            return redirect()->route('login')->with('toast', [
                'message' => 'Pendaftaran berhasil. Silakan login.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Register Error: ' . $e->getMessage());

            return back()->withErrors([
                'storeError' => 'Terjadi kesalahan saat menyimpan pengguna. Mohon coba lagi.'
            ])->withInput()->with('toast', [
                'message' => 'Terjadi kesalahan saat menyimpan pengguna. Mohon coba lagi.',
                'type' => 'error'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('beranda')->with('toast', [
            'message' => 'Logout berhasil!',
            'type' => 'success'
        ]);;
    }

    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email salah.',
            'password.required' => 'Password harus diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $userRole = $user->role;

            Login::create([
                'users' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'logged_in_at' => now(),
            ]);

            $request->session()->put([
                'login_time' => now()->toDateTimeString(),
                'nama' => $user->name,
                'id_user' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at
            ]);

            if (in_array($userRole, ['admin', 'user'])) {
                return redirect()->route('materi')->with('toast', [
                    'message' => 'Login berhasil!',
                    'type' => 'success'
                ]);
            }

            return back()->with('toast', [
                'message' => 'Login gagal, role pengguna tidak dikenali.',
                'type' => 'error'
            ]);
        }

        return back()->withErrors([
            'loginError' => 'Email atau password salah.',
        ])->with('toast', [
            'message' => 'Email atau password salah.',
            'type' => 'error'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function profil()
    {
        $user = Auth::user();
        $qrCode = QrCode::size(150)->generate(url('/user/' . $user->id));
        $logins = Login::where('users', Auth::id())
            ->latest('logged_in_at')
            ->take(5)
            ->get();

        return view('profile', compact('user', 'qrCode', 'logins'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function updateName(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 255 karakter.',
        ]);

        try {
            $user = User::findOrFail($id);

            if ($user->id !== Auth::id()) {
                return back()->with('toast', [
                    'message' => 'Anda tidak diizinkan mengubah nama pengguna ini.',
                    'type' => 'error'
                ]);
            }

            $user->name = $request->name;
            $user->save();

            // Update session
            session()->put('nama', $user->name);

            return back()->with('toast', [
                'message' => 'Nama berhasil diperbarui.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with('toast', [
                'message' => 'Gagal memperbarui nama. Error: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'old_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Password lama tidak cocok.'])
                ->with('toast', [
                    'message' => 'Password lama tidak cocok.',
                    'type' => 'error'
                ]);
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('toast', [
                'message' => 'Password berhasil diperbarui.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return back()->with('toast', [
                'message' => 'Gagal memperbarui password. Error: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
