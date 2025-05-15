<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('skpd')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $daftar_skpd = Skpd::all();
        return view('users.form', [
            'title' => 'Tambah User',
            'action' => route('users.store'),
            'method' => 'POST',
            'user' => null,
            'daftar_skpd' => $daftar_skpd
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users,username',
            'skpd_id' => 'required|exists:skpd,id',
        ]);

        // Membuat user dengan username yang diberikan dan skpd_id, email dan password default
        User::create([
            'name' => 'Default User', // Nama user tetap bisa dikosongkan atau diberikan nilai default
            'username' => $request->username,
            'email' => $request->username . '@balangan.go.id', // Email default dengan username
            'password' => Hash::make('12345678'), // Password default
            'skpd_id' => $request->skpd_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        $daftar_skpd = Skpd::all();
        return view('users.form', [
            'title' => 'Edit User',
            'action' => route('users.update', $user->id),
            'method' => 'PUT',
            'user' => $user,
            'daftar_skpd' => $daftar_skpd
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'skpd_id' => 'required|exists:skpd,id',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->username . '@balangan.go.id',
            'skpd_id' => $request->skpd_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'password' => Hash::make('12345678'),
        ]);

        // Set session message untuk menunjukkan bahwa password telah direset
        return redirect()->route('users.index')->with('success', 'Password untuk ' . $user->username . ' telah berhasil direset menjadi 12345678');
    }

    public function changePassword()
    {
        $user = auth()->user();
        return view('users.change-password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $messages = [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal :min karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ];

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], $messages);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini tidak valid.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('dashboard')->with('success', 'Password berhasil diperbarui');
    }
}
