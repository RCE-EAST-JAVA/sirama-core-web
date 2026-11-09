<x-guest-layout>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <div x-data="loadTestEngine()" x-cloak class="space-y-5">
        {{-- Header (Matching Login Style) --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Database Load Test</h1>
            <p class="text-gray-500 text-sm">Uji performa query database SIRAMA secara real-time</p>
        </div>

        {{-- Parameters Grid (Total Data & Batch Size) --}}
        <div class="grid grid-cols-2 gap-3.5">
            {{-- Total Data Card --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center shadow-xs">
                <span class="text-xs font-semibold tracking-wider text-gray-400 uppercase block">
                    TOTAL DATA
                </span>
                <span class="text-3xl font-bold text-gray-900 mt-1 block">
                    {{ $total_data }}
                </span>
            </div>

            {{-- Batch Size Card --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center shadow-xs">
                <span class="text-xs font-semibold tracking-wider text-gray-400 uppercase block">
                    UKURAN BATCH
                </span>
                <div class="mt-1">
                    <span class="text-3xl font-bold text-brand-600">10</span>
                    <span class="text-xs font-semibold text-gray-500 block">/ request</span>
                </div>
            </div>
        </div>

        {{-- Status & Result Card --}}
        <div>
            {{-- 1. Idle State --}}
            <div x-show="status === 'idle'" class="bg-gray-50 border border-dashed border-gray-200 rounded-xl p-4 text-center">
                <p class="text-sm font-medium text-gray-600">Siap Menguji Performa Database</p>
                <p class="text-xs text-gray-400 mt-1">Tekan tombol di bawah untuk menjalankan simulasi 10 request.</p>
            </div>

            {{-- 2. Running State --}}
            <div x-show="status === 'running'" class="bg-brand-50/70 border border-brand-200 rounded-xl p-4 space-y-2.5">
                <div class="flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-brand-900">Sedang Menguji Database...</p>
                        <p class="text-xs text-brand-700">Memproses <span x-text="completedRequests"></span> dari 10 request</p>
                    </div>
                </div>
                <div class="w-full bg-brand-200/60 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-brand-600 h-1.5 rounded-full transition-all duration-150" :style="`width: ${(completedRequests / 10) * 100}%`"></div>
                </div>
            </div>

            {{-- 3. Success State (Test Passed!) --}}
            <div x-show="status === 'passed'" class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-4 space-y-2">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="text-base font-bold text-emerald-800">Test Passed!</span>
                </div>
                <p class="text-xs sm:text-sm text-gray-600">Proses pemuatan selesai dengan sukses.</p>
                <div class="pt-2 flex items-baseline justify-between border-t border-emerald-200/80">
                    <span class="text-xs sm:text-sm font-semibold text-gray-700">Durasi total:</span>
                    <span class="text-sm sm:text-base font-extrabold text-emerald-700" x-text="totalDurationFormatted"></span>
                </div>
            </div>

            {{-- 4. Failed State --}}
            <div x-show="status === 'failed'" class="bg-red-50 border border-red-200 rounded-xl p-4 text-xs text-red-700 space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span>Uji Beban Gagal</span>
                </div>
                <p x-text="errorMessage"></p>
            </div>
        </div>

        {{-- Action Button (Styled Identical to Login Button) --}}
        <div>
            <button
                type="button"
                @click="startLoadTest()"
                :disabled="status === 'running'"
                class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-brand-600 border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:bg-brand-700 focus:bg-brand-700 active:bg-brand-800 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer shadow-sm"
            >
                <template x-if="status !== 'running'">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </template>
                <template x-if="status === 'running'">
                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </template>
                <span x-text="status === 'running' ? 'Memproses 10 Request...' : 'Mulai Load Test'"></span>
            </button>
        </div>

        {{-- Detailed Breakdown --}}
        <div x-show="status === 'passed'" class="border-t border-gray-100 pt-3 space-y-2.5">
            <button
                type="button"
                @click="showDetails = !showDetails"
                class="w-full flex items-center justify-between text-xs font-semibold text-gray-500 hover:text-brand-600 transition-colors"
            >
                <span>Lihat Rincian 10 Request</span>
                <svg class="w-4 h-4 transform transition-transform" :class="showDetails ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="showDetails" class="space-y-2 pt-1">
                {{-- Quick Metrics Summary --}}
                <div class="grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="bg-gray-50 rounded-lg p-2 border border-gray-100">
                        <div class="text-gray-400">Rata-rata</div>
                        <div class="font-bold text-gray-800" x-text="avgDurationFormatted"></div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2 border border-gray-100">
                        <div class="text-gray-400">Tercepat</div>
                        <div class="font-bold text-emerald-600" x-text="minDurationFormatted"></div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-2 border border-gray-100">
                        <div class="text-gray-400">Terlambat</div>
                        <div class="font-bold text-amber-600" x-text="maxDurationFormatted"></div>
                    </div>
                </div>

                {{-- Requests Table --}}
                <div class="overflow-x-auto max-h-44 overflow-y-auto rounded-lg border border-gray-100">
                    <table class="w-full text-[11px] text-left">
                        <thead class="bg-gray-50 text-gray-500 sticky top-0">
                            <tr>
                                <th class="px-2.5 py-1.5">#</th>
                                <th class="px-2.5 py-1.5">Latensi Client</th>
                                <th class="px-2.5 py-1.5">Server DB</th>
                                <th class="px-2.5 py-1.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            <template x-for="item in results" :key="item.index">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2.5 py-1.5 font-medium" x-text="'Req ' + item.index"></td>
                                    <td class="px-2.5 py-1.5" x-text="item.clientTimeMs.toFixed(2) + ' ms'"></td>
                                    <td class="px-2.5 py-1.5" x-text="item.serverTimeMs ? item.serverTimeMs.toFixed(2) + ' ms' : '-'"></td>
                                    <td class="px-2.5 py-1.5">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                            200 OK
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Footer (Matching Login Style) --}}
        <p class="mt-8 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} SIRAMA — Kecamatan Sukosari
        </p>
    </div>

    {{-- Alpine.js Concurrency Controller --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loadTestEngine', () => ({
                status: 'idle',
                completedRequests: 0,
                totalRequests: 10,
                totalDurationMs: 0,
                totalDurationFormatted: '0.00 ms',
                avgDurationFormatted: '0.00 ms',
                minDurationFormatted: '0.00 ms',
                maxDurationFormatted: '0.00 ms',
                errorMessage: '',
                showDetails: false,
                results: [],

                async startLoadTest() {
                    this.status = 'running';
                    this.completedRequests = 0;
                    this.errorMessage = '';
                    this.results = [];
                    this.showDetails = false;

                    const globalStartTime = performance.now();
                    const batchSize = 10;
                    const endpoint = '{{ route("load-test.run") }}';
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                    const requestPromises = Array.from({ length: batchSize }, (_, i) => {
                        const index = i + 1;
                        const reqStartTime = performance.now();

                        return fetch(endpoint, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ batch_index: index })
                        })
                        .then(async (response) => {
                            const reqEndTime = performance.now();
                            const clientTimeMs = reqEndTime - reqStartTime;

                            if (!response.ok) {
                                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                            }

                            const data = await response.json();
                            this.completedRequests++;

                            return {
                                index: index,
                                success: true,
                                clientTimeMs: clientTimeMs,
                                serverTimeMs: data.server_execution_time_ms ?? 0,
                                data: data
                            };
                        })
                        .catch((err) => {
                            this.completedRequests++;
                            return {
                                index: index,
                                success: false,
                                clientTimeMs: performance.now() - reqStartTime,
                                error: err.message
                            };
                        });
                    });

                    const settledResults = await Promise.all(requestPromises);
                    const globalEndTime = performance.now();

                    this.totalDurationMs = globalEndTime - globalStartTime;
                    this.totalDurationFormatted = this.totalDurationMs.toFixed(2) + ' ms';

                    const failedItems = settledResults.filter(r => !r.success);
                    if (failedItems.length > 0) {
                        this.status = 'failed';
                        this.errorMessage = `${failedItems.length} dari ${batchSize} request gagal dieksekusi.`;
                        return;
                    }

                    this.results = settledResults.sort((a, b) => a.index - b.index);

                    const clientTimes = this.results.map(r => r.clientTimeMs);
                    const sum = clientTimes.reduce((acc, curr) => acc + curr, 0);
                    const avg = sum / clientTimes.length;
                    const min = Math.min(...clientTimes);
                    const max = Math.max(...clientTimes);

                    this.avgDurationFormatted = avg.toFixed(2) + ' ms';
                    this.minDurationFormatted = min.toFixed(2) + ' ms';
                    this.maxDurationFormatted = max.toFixed(2) + ' ms';

                    this.status = 'passed';
                }
            }));
        });
    </script>
</x-guest-layout>
