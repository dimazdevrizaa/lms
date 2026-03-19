<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-3xl leading-tight" style="color: #25671E;">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <div class="text-sm text-gray-600">
                {{ date('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: #F7F0F0; min-height: calc(100vh - 100px);">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-purple-100 to-purple-50 rounded-lg p-8 shadow-md border-l-4" style="border-color: #25671E;">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-bold mb-2" style="color: #25671E;">
                                {{ __('Hi, ') . Auth::user()->name }}
                            </h3>
                            <p class="text-gray-600 text-lg">
                                {{ __('Siap memulai hari ini dengan pencapaian baru?') }}
                            </p>
                        </div>
                        <div class="hidden md:block">
                            <svg class="w-32 h-32 opacity-20" fill="#25671E" viewBox="0 0 100 100">
                                <circle cx="50" cy="30" r="15"/>
                                <path d="M 50 50 Q 30 60, 25 80 L 75 80 Q 70 60, 50 50"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metrics Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Metric 1 -->
                <div class="bg-white rounded-lg p-6 shadow-md border-t-4" style="border-color: #F2B50B;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">{{ __('Total Students') }}</p>
                            <p class="text-4xl font-bold mt-2" style="color: #25671E;">
                                {{ rand(45, 150) }}
                            </p>
                        </div>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center" style="background-color: #F2B50B20;">
                            <span class="text-2xl">👥</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-4">{{ __('↑ 12% dari minggu lalu') }}</p>
                </div>

                <!-- Metric 2 -->
                <div class="bg-white rounded-lg p-6 shadow-md border-t-4" style="border-color: #48A111;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">{{ __('Attendance') }}</p>
                            <p class="text-4xl font-bold mt-2" style="color: #25671E;">
                                {{ rand(85, 98) }}%
                            </p>
                        </div>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center" style="background-color: #48A11120;">
                            <span class="text-2xl">✓</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-4">{{ __('Kehadiran bulan ini') }}</p>
                </div>

                <!-- Metric 3 -->
                <div class="bg-white rounded-lg p-6 shadow-md border-t-4" style="border-color: #25671E;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">{{ __('Assignments') }}</p>
                            <p class="text-4xl font-bold mt-2" style="color: #25671E;">
                                {{ rand(15, 40) }}
                            </p>
                        </div>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center" style="background-color: #25671E20;">
                            <span class="text-2xl">📋</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-4">{{ __('Tugas aktif') }}</p>
                </div>

                <!-- Metric 4 -->
                <div class="bg-white rounded-lg p-6 shadow-md border-t-4" style="border-color: #48A111;">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">{{ __('Materials') }}</p>
                            <p class="text-4xl font-bold mt-2" style="color: #25671E;">
                                {{ rand(20, 80) }}
                            </p>
                        </div>
                        <div class="w-16 h-16 rounded-full flex items-center justify-center" style="background-color: #48A11120;">
                            <span class="text-2xl">📚</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-4">{{ __('Materi tersedia') }}</p>
                </div>
            </div>

            <!-- Content Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Activity -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-50 to-yellow-20 p-6 border-b-2" style="border-color: #F2B50B;">
                            <h4 class="text-lg font-bold" style="color: #25671E;">
                                {{ __('Aktivitas Terbaru') }}
                            </h4>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Activity Item 1 -->
                            <div class="flex items-start gap-4 pb-4 border-b">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: #F2B50B20;">
                                    <span class="text-lg">📝</span>
                                </div>
                                <div class="flex-grow">
                                    <p class="font-semibold" style="color: #25671E;">{{ __('Tugas: Matematika Lanjutan') }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ __('Deadline: 2 hari lagi') }}</p>
                                    <div class="mt-2 bg-gray-200 h-2 rounded-full overflow-hidden">
                                        <div class="bg-gradient-to-r to-green-400" style="width: 65%; background-color: #48A111; height: 100%;"></div>
                                    </div>
                                </div>
                                <button class="px-3 py-1 rounded text-sm font-medium text-white" style="background-color: #25671E;">
                                    {{ __('Buka') }}
                                </button>
                            </div>

                            <!-- Activity Item 2 -->
                            <div class="flex items-start gap-4 pb-4 border-b">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: #48A11120;">
                                    <span class="text-lg">🎓</span>
                                </div>
                                <div class="flex-grow">
                                    <p class="font-semibold" style="color: #25671E;">{{ __('Konten Baru: Bahasa Inggris') }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ __('Upload: Pukul 10:30') }}</p>
                                    <div class="mt-3 inline-block px-3 py-1 rounded-full text-xs font-medium text-white" style="background-color: #48A111;">
                                        {{ __('Baru') }}
                                    </div>
                                </div>
                                <button class="px-3 py-1 rounded text-sm font-medium text-white" style="background-color: #25671E;">
                                    {{ __('Lihat') }}
                                </button>
                            </div>

                            <!-- Activity Item 3 -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" style="background-color: #25671E20;">
                                    <span class="text-lg">🏆</span>
                                </div>
                                <div class="flex-grow">
                                    <p class="font-semibold" style="color: #25671E;">{{ __('Nilai Ujian Fisika') }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ __('Nilai: 87 | Ranking: Top 10') }}</p>
                                    <div class="mt-3 text-sm font-medium text-green-600">
                                        ✓ {{ __('Bernilai') }}
                                    </div>
                                </div>
                                <button class="px-3 py-1 rounded text-sm font-medium text-white" style="background-color: #25671E;">
                                    {{ __('Detail') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="space-y-6">
                    <!-- Coming Soon -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-20 p-6 border-b-2" style="border-color: #25671E;">
                            <h4 class="text-lg font-bold" style="color: #25671E;">
                                {{ __('Jadwal Hari Ini') }}
                            </h4>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center gap-3 pb-3 border-b">
                                <span class="text-xl">📚</span>
                                <div>
                                    <p class="font-semibold text-sm" style="color: #25671E;">Matematika</p>
                                    <p class="text-xs text-gray-600">09:00 - 10:30</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 pb-3 border-b">
                                <span class="text-xl">🌍</span>
                                <div>
                                    <p class="font-semibold text-sm" style="color: #25671E;">Bahasa Inggris</p>
                                    <p class="text-xs text-gray-600">11:00 - 12:30</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xl">🔬</span>
                                <div>
                                    <p class="font-semibold text-sm" style="color: #25671E;">Sains</p>
                                    <p class="text-xs text-gray-600">13:30 - 15:00</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-green-20 p-6 border-b-2" style="border-color: #48A111;">
                            <h4 class="text-lg font-bold" style="color: #25671E;">
                                {{ __('Progress Bulanan') }}
                            </h4>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-center">
                                <div class="relative w-32 h-32">
                                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                                        <circle cx="60" cy="60" r="54" fill="none" stroke="#E5E7EB" stroke-width="8" />
                                        <circle cx="60" cy="60" r="54" fill="none" stroke="#48A111" stroke-width="8" stroke-dasharray="84.78 219.80" stroke-linecap="round" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-2xl font-bold" style="color: #25671E;">75%</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-center text-sm text-gray-600 mt-4">
                                {{ __('Selesai 24 dari 32 tugas') }}
                            </p>
                        </div>
                    </div>
        </div>
    </div>
</x-app-layout>
