<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<!-- Icons from Heroicons -->
<script src="https://unpkg.com/@heroicons/v1/outline"></script>

<div class="p-6 space-y-6">
    <!-- Welcome Section -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-gray-600">Selamat datang, <?= session()->get('nama') ?></p>
        </div>
        <div class="text-sm text-gray-600">
            <?= date('l, d F Y') ?>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Kamar -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-600 text-sm">Total Kamar</h2>
                    <p class="text-2xl font-bold text-gray-800">20</p>
                </div>
            </div>
        </div>

        <!-- Kamar Tersedia -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-600 text-sm">Kamar Tersedia</h2>
                    <p class="text-2xl font-bold text-gray-800">15</p>
                </div>
            </div>
        </div>

        <!-- Booking Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-600 text-sm">Booking Hari Ini</h2>
                    <p class="text-2xl font-bold text-gray-800">8</p>
                </div>
            </div>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-gray-600 text-sm">Pendapatan Hari Ini</h2>
                    <p class="text-2xl font-bold text-gray-800">Rp 5.2M</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Bookings -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800">Booking Terbaru</h3>
                <div class="mt-4 space-y-4">
                    <!-- Booking Item -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-800">John Doe</p>
                            <p class="text-sm text-gray-600">Kamar 101 - Deluxe</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-600">Check-in</p>
                            <p class="text-gray-800">24 Mar 2024</p>
                        </div>
                    </div>
                    <!-- More booking items... -->
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <button class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    Tambah Booking
                </button>
                <button class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                    Tambah Layanan
                </button>
                <button class="w-full bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors">
                    Lihat Laporan
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>