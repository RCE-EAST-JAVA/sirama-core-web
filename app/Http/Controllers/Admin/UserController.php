<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::whereIn('role', ['admin_desa', 'admin_kecamatan'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nik'         => ['required', 'string', 'digits:16', 'unique:users'],
            'name'        => ['required', 'string', 'max:255'],
            'no_whatsapp' => ['required', 'string', 'max:20'],
            'role'        => ['required', 'in:admin_desa,admin_kecamatan'],
            'desa'        => ['nullable', 'string', 'max:255', 'required_if:role,admin_desa'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'nik'         => $request->nik,
            'name'        => $request->name,
            'no_whatsapp' => $request->no_whatsapp,
            'role'        => $request->role,
            'desa'        => $request->role === 'admin_desa' ? $request->desa : null,
            'password'    => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        abort_if(! in_array($user->role, ['admin_desa', 'admin_kecamatan']), 403);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        abort_if(! in_array($user->role, ['admin_desa', 'admin_kecamatan']), 403);

        $request->validate([
            'nik'         => ['required', 'string', 'digits:16', 'unique:users,nik,'.$user->id],
            'name'        => ['required', 'string', 'max:255'],
            'no_whatsapp' => ['required', 'string', 'max:20'],
            'role'        => ['required', 'in:admin_desa,admin_kecamatan'],
            'desa'        => ['nullable', 'string', 'max:255', 'required_if:role,admin_desa'],
            'password'    => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'nik'         => $request->nik,
            'name'        => $request->name,
            'no_whatsapp' => $request->no_whatsapp,
            'role'        => $request->role,
            'desa'        => $request->role === 'admin_desa' ? $request->desa : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if(! in_array($user->role, ['admin_desa', 'admin_kecamatan']), 403);

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun berhasil dihapus.');
    }
}
