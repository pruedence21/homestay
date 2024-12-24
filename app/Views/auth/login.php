<!DOCTYPE html>
<html>
<head>
    <title>Login - Hotel Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center">Login Hotel Manager</h2>
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('auth/authenticate') ?>" method="post">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>