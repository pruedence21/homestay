<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div x-data="crudHandler({
    baseUrl: '<?= base_url('admin/kamar') ?>',
    createTitle: 'Tambah Kamar',
    editTitle: 'Edit Kamar',
    defaultData: {
        nomor_kamar: '',
        tipe_kamar: '',
        harga: '',
        status: 'tersedia',
        deskripsi: ''
    }
})">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Kamar</h1>
        <button @click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Kamar
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Kamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($kamar as $index => $k): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $index + 1 ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $k['nomor_kamar'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $k['tipe_kamar'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp <?= number_format($k['harga'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?= $k['status'] == 'tersedia' ? 'bg-green-100 text-green-800' : 
                                   ($k['status'] == 'terisi' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                <?= ucfirst($k['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button @click="edit(<?= json_encode($k) ?>)" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button @click="deleteData(<?= $k['id_kamar'] ?>)" class="text-red-600 hover:text-red-900">Hapus</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" @click="closeModal()">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form @submit.prevent="save()">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="modalTitle"></h3>
                        
                        <!-- Validation Messages -->
                        <template x-if="errors">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <template x-for="error in Object.values(errors)" :key="error">
                                    <p x-text="error"></p>
                                </template>
                            </div>
                        </template>

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            <!-- Nomor Kamar -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Kamar</label>
                                <div class="relative">
                                    <input type="text" x-model="formData.nomor_kamar" 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent placeholder-gray-400 transition duration-150 ease-in-out"
                                        placeholder="Masukkan nomor kamar"
                                        required>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <svg x-show="formData.nomor_kamar" class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Tipe Kamar -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tipe Kamar</label>
                                <div class="relative">
                                    <select x-model="formData.tipe_kamar" 
                                        class="appearance-none w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent bg-white"
                                        required>
                                        <option value="">Pilih Tipe Kamar</option>
                                        <option value="Standard">Standard</option>
                                        <option value="Deluxe">Deluxe</option>
                                        <option value="Suite">Suite</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
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

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="flex items-center justify-center px-4 py-2 border rounded-lg cursor-pointer"
                                        :class="formData.status === 'tersedia' ? 'bg-green-50 border-green-200 text-green-600' : 'border-gray-200'">
                                        <input type="radio" x-model="formData.status" value="tersedia" class="hidden">
                                        <span class="text-sm">Tersedia</span>
                                    </label>
                                    <label class="flex items-center justify-center px-4 py-2 border rounded-lg cursor-pointer"
                                        :class="formData.status === 'terisi' ? 'bg-red-50 border-red-200 text-red-600' : 'border-gray-200'">
                                        <input type="radio" x-model="formData.status" value="terisi" class="hidden">
                                        <span class="text-sm">Terisi</span>
                                    </label>
                                    <label class="flex items-center justify-center px-4 py-2 border rounded-lg cursor-pointer"
                                        :class="formData.status === 'maintenance' ? 'bg-yellow-50 border-yellow-200 text-yellow-600' : 'border-gray-200'">
                                        <input type="radio" x-model="formData.status" value="maintenance" class="hidden">
                                        <span class="text-sm">Maintenance</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                                <textarea x-model="formData.deskripsi" 
                                    rows="3" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent placeholder-gray-400 resize-none"
                                    placeholder="Masukkan deskripsi kamar"></textarea>
                            </div>
                        </div>

                        <!-- Footer Buttons -->
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                            <button type="button" @click="closeModal()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                Batal
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                                :class="{'opacity-50 cursor-not-allowed': loading}"
                                :disabled="loading">
                                <span x-text="loading ? 'Menyimpan...' : 'Simpan'"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>