<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div x-data="crudHandler({
    baseUrl: '<?= base_url('admin/layanan') ?>',
    createTitle: 'Tambah Layanan',
    editTitle: 'Edit Layanan',
    defaultData: {
        nama_layanan: '',
        harga: '',
        kategori: '',
        deskripsi: ''
    }
})">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Layanan</h1>
        <button @click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Layanan
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Layanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($layanan as $index => $l): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $index + 1 ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $l['nama_layanan'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= ucfirst($l['kategori']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">Rp <?= number_format($l['harga'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button @click="edit(<?= json_encode($l) ?>)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button @click="deleteData(<?= $l['id_layanan'] ?>)" class="text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <div x-show="showModal" 
        class="fixed inset-0 z-50 overflow-y-auto" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity" @click="closeModal()">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <form @submit.prevent="save">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" x-text="modalTitle"></h3>
                        
                        <div class="space-y-6">
                            <!-- Nama Layanan -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Layanan</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <input type="text" x-model="formData.nama_layanan" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent placeholder-gray-400"
                                        placeholder="Masukkan nama layanan"
                                        required>
                                </div>
                            </div>

                            <!-- Kategori -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <select x-model="formData.kategori" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-white"
                                        required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="makanan">Makanan</option>
                                        <option value="extrabed">Extra Bed</option>
                                        <option value="snack">Snack</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Harga -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga</label>
                                <div class="relative rounded-lg shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" x-model="formData.harga"
                                        class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent placeholder-gray-400"
                                        placeholder="0"
                                        required>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                                <textarea x-model="formData.deskripsi" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent placeholder-gray-400"
                                    rows="3"
                                    placeholder="Masukkan deskripsi layanan"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            :disabled="loading">
                            <span x-text="loading ? 'Menyimpan...' : 'Simpan'"></span>
                        </button>
                        <button type="button" @click="closeModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
