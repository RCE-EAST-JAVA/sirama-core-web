<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class WargaController extends Controller
{
    public function index(): View
    {
        $query = User::where('role', 'warga');

        // Admin desa hanya bisa lihat warga di desanya
        if (auth()->user()->role === 'admin_desa') {
            $query->where('desa', auth()->user()->desa);
        }

        $wargas = $query->latest()->paginate(15);

        return view('admin.warga.index', compact('wargas'));
    }

    public function showResetForm(User $warga_user): View
    {
        // Custom route binding already ensures:
        // 1. User is a warga (role check)
        // 2. Admin desa can only access warga in their desa
        // No additional authorization needed here

        return view('admin.warga.reset-password', ['user' => $warga_user]);
    }

    public function resetPassword(Request $request, User $warga_user): RedirectResponse
    {
        // Custom route binding already ensures:
        // 1. User is a warga (role check)
        // 2. Admin desa can only access warga in their desa
        // No additional authorization needed here

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal :min karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $warga_user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.warga.index')
            ->with('success', 'Password warga berhasil diperbarui.');
    }
}
