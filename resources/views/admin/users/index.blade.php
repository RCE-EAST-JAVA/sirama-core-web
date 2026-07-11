<x-dashboard-layout title="Manajemen User" pageTitle="Manajemen Akun Petugas">

    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.users.create') }}"
           class="bg-blue-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            + Tambah Akun
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">{{ $users->total() }} akun petugas</h2>
        </div>

        @if($users->isEmpty())
            <div class="px-6 py-16 text-center text-gray-400 text-sm">
                Belum ada akun petugas.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">NIK</th>
                            <th class="px-6 py-3 text-left">No. WhatsApp</th>
                            <th class="px-6 py-3 text-left">Role</th>
                            <th class="px-6 py-3 text-left">Desa</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 font-mono text-gray-500">{{ $user->nik }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->no_whatsapp }}</td>
                                <td class="px-6 py-4">
                                    @if($user->role === 'admin_desa')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Admin Desa</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Admin Kecamatan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->desa ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                              x-data
                                              @submit.prevent="if(confirm('Hapus akun {{ addslashes($user->name) }}?')) $el.submit()">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>

</x-dashboard-layout>
