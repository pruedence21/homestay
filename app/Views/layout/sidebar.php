<div x-data="{ isOpen: false }" class="relative z-10">
    <!-- Mobile menu button -->
    <button @click="isOpen = !isOpen" 
        class="lg:hidden fixed top-4 left-4 z-20 p-2 rounded-lg text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white transition-colors duration-200">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path x-show="!isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            <path x-show="isOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    <!-- Sidebar -->
    <div :class="{'translate-x-0': isOpen, '-translate-x-full': !isOpen}" 
        class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-gray-900 to-gray-800 transform lg:translate-x-0 transition duration-300 ease-in-out lg:relative z-30 shadow-xl overflow-y-auto">
        
        <!-- Logo Section -->
        <div class="flex items-center justify-center h-16 bg-gray-800 border-b border-gray-700">
            <span class="text-white text-xl font-bold tracking-wider">HOTEL MANAGER</span>
        </div>

        <?php if(session()->get('role') == 'admin'): ?>
            <!-- Admin Navigation -->
            <nav class="mt-6 px-3 space-y-1">
                <!-- Dashboard -->
                <a href="<?= base_url('admin/dashboard') ?>" 
                    class="group flex items-center px-3 py-2.5 text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-800 hover:text-white <?= current_url() == base_url('admin/dashboard') ? 'bg-gray-800 text-white' : '' ?>">
                    <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Divider -->
                <div class="border-t border-gray-700 my-3"></div>

                <!-- Room Management -->
                <div class="space-y-1">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Manajemen Kamar</p>
                    <a href="<?= base_url('admin/kamar') ?>" 
                        class="group flex items-center px-3 py-2.5 text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-800 hover:text-white <?= current_url() == base_url('admin/kamar') ? 'bg-gray-800 text-white' : '' ?>">
                        <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="font-medium">Data Kamar</span>
                    </a>

                    <a href="<?= base_url('admin/booking') ?>" 
                        class="group flex items-center px-3 py-2.5 text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-800 hover:text-white <?= current_url() == base_url('admin/booking') ? 'bg-gray-800 text-white' : '' ?>">
                        <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium">Pemesanan</span>
                    </a>
                </div>

                <!-- Services Management -->
                <div class="space-y-1 mt-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Layanan</p>
                    <a href="<?= base_url('admin/layanan') ?>" 
                        class="group flex items-center px-3 py-2.5 text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-800 hover:text-white <?= current_url() == base_url('admin/layanan') ? 'bg-gray-800 text-white' : '' ?>">
                        <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="font-medium">Layanan</span>
                    </a>
                </div>

                <!-- Reports -->
                <div class="space-y-1 mt-4">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Laporan</p>
                    <a href="<?= base_url('admin/laporan') ?>" 
                        class="group flex items-center px-3 py-2.5 text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-800 hover:text-white <?= current_url() == base_url('admin/laporan') ? 'bg-gray-800 text-white' : '' ?>">
                        <svg class="mr-3 h-5 w-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="font-medium">Laporan</span>
                    </a>
                </div>
            </nav>
        <?php else: ?>
            <nav class="mt-8 space-y-1 px-2">
                <a href="<?= base_url('kasir/dashboard') ?>" class="group flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="<?= base_url('kasir/booking') ?>" class="group flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/>
                    </svg>
                    Booking
                </a>

                <a href="<?= base_url('kasir/layanan') ?>" class="group flex items-center px-2 py-2 text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">
                    <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Layanan
                </a>
            </nav>
        <?php endif; ?>

        <!-- User Info -->
        <div class="absolute bottom-0 w-full border-t border-gray-700">
            <div class="px-4 py-4">
                <div class="flex items-center space-x-3 text-gray-300">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium"><?= session()->get('nama') ?></p>
                        <p class="text-xs text-gray-500"><?= ucfirst(session()->get('role')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
