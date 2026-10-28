<x-dashboard-layout title="Manajemen Warga" pageTitle="Manajemen Data Warga">

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">{{ $wargas->total() }} akun warga</h2>
        </div>

        @if($wargas->isEmpty())
            <div class="px-6 py-16 text-center text-gray-400 text-sm">
                Belum ada akun warga terdaftar.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3 text-left">NIK</th>
                            <th class="px-6 py-3 text-left">Nama</th>
                            <th class="px-6 py-3 text-left">No. WhatsApp</th>
                            <th class="px-6 py-3 text-left">Desa</th>
                            <th class="px-6 py-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($wargas as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-gray-500">{{ $user->nik }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->no_whatsapp }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->desa ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.warga.reset.form', $user) }}"
                                       class="text-blue-600 hover:text-blue-800 font-medium">Reset Password</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($wargas->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $wargas->links() }}
                </div>
            @endif
        @endif
    </div>

</x-dashboard-layout>
