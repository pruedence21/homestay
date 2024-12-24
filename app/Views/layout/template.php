<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?? 'Hotel Manager' ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= base_url() ?>">
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Additional Styles -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">

    <!-- Scripts -->
    <script>
        // Utility Functions
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(angka);
        }

        // Define baseUrl for all handlers
        const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>

    <!-- Custom Handlers -->
    <script src="<?= base_url('js/crud-handler.js') ?>"></script>
    <script src="<?= base_url('js/booking-handler.js') ?>"></script>
    <script src="<?= base_url('js/layanan-handler.js') ?>"></script>

    <!-- Tailwind Configuration -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        <?= $this->include('layout/sidebar') ?>
        
        <!-- Main Content -->
        <div class="flex-1 min-h-screen">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800"><?= $title ?? 'Dashboard' ?></h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Profile dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                <span><?= session()->get('nama') ?></span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                <a href="<?= base_url('auth/logout') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="px-4 sm:px-6 lg:px-8 py-8">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>
</body>
</html>
