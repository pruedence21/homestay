<?= $this->extend('layout/template') ?>
   <!-- Custom Handlers -->
   <script src="<?= base_url('js/crud-handler.js') ?>"></script>
    <script src="<?= base_url('js/booking-handler.js') ?>"></script>
    <script src="<?= base_url('js/layanan-handler.js') ?>"></script>
<?= $this->section('content') ?>
<div x-data="bookingHandler()" x-init="init()">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Booking</h1>
        <button @click="create" 
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded flex items-center"
            onclick="console.log('Button clicked')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Booking
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Kamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Tamu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check Out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($booking as $index => $booking): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $index + 1 ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $booking['nomor_kamar'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= $booking['nama_tamu'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= date('d/m/Y', strtotime($booking['checkin'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= date('d/m/Y', strtotime($booking['checkout'])) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?= $booking['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($booking['status'] == 'confirmed' ? 'bg-blue-100 text-blue-800' : 
                                    ($booking['status'] == 'checkin' ? 'bg-green-100 text-green-800' : 
                                    ($booking['status'] == 'checkout' ? 'bg-gray-100 text-gray-800' : 
                                    'bg-red-100 text-red-800'))) ?>">
                                <?= ucfirst($booking['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <!-- Status Buttons -->
                            <?php if($booking['status'] == 'pending'): ?>
                                <button @click="updateStatus(<?= $booking['id_booking'] ?>, 'confirmed')" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md">
                                    Konfirmasi
                                </button>
                            <?php elseif($booking['status'] == 'confirmed'): ?>
                                <button @click="updateStatus(<?= $booking['id_booking'] ?>, 'checkin')" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md">
                                    Check In
                                </button>
                            <?php elseif($booking['status'] == 'checkin'): ?>
                                <button @click="updateStatus(<?= $booking['id_booking'] ?>, 'checkout')" 
                                    class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded-md">
                                    Check Out
                                </button>
                            <?php endif; ?>
                            
                            <!-- Edit & Delete -->
                            <button @click="edit(<?= json_encode($booking) ?>)" 
                                class="text-blue-600 hover:text-blue-900">
                                Edit
                            </button>
                            <button @click="deleteData(<?= $booking['id_booking'] ?>)" 
                                class="text-red-600 hover:text-red-900">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" @click="showModal = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form @submit.prevent="save">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="modalTitle"></h3>

                        <!-- Kamar -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kamar</label>
                            <select x-model="formData.id_kamar" @change="calculateTotalHarga" 
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Kamar</option>
                                <template x-for="kamar in kamarTersedia" :key="kamar.id_kamar">
                                    <option :value="kamar.id_kamar" x-text="`${kamar.nomor_kamar} - ${kamar.tipe_kamar} (Rp ${kamar.harga})`"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Check In</label>
                                <input type="date" x-model="formData.checkin" @change="checkAvailability(); calculateTotalHarga()"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Check Out</label>
                                <input type="date" x-model="formData.checkout" @change="checkAvailability(); calculateTotalHarga()"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Data Tamu -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Tamu</label>
                                <input type="text" x-model="formData.nama_tamu" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" x-model="formData.email"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                                <input type="tel" x-model="formData.no_telp"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Tamu</label>
                                <input type="number" x-model="formData.jumlah_tamu" min="1"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Total Harga -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total Harga</label>
                            <div class="text-2xl font-bold text-gray-900" x-text="`Rp ${formData.total_harga.toLocaleString()}`"></div>
                        </div>

                        <!-- Layanan Tambahan -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium mb-4">Layanan Tambahan</h3>
                            
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex-1">
                                    <select x-model="selectedLayanan" class="rounded-md border-gray-300">
                                        <option value="">Pilih Layanan</option>
                                        <template x-for="layanan in layananList" :key="layanan.id_layanan">
                                            <option :value="layanan.id_layanan" 
                                                x-text="`${layanan.nama_layanan} - Rp ${layanan.harga.toLocaleString()}`">
                                            </option>
                                        </template>
                                    </select>
                                    <input type="number" x-model="jumlahLayanan" min="1" 
                                        class="rounded-md border-gray-300 w-24 ml-2" placeholder="Jumlah">
                                    <button @click="addLayanan()" 
                                        class="bg-green-500 text-white px-4 py-2 rounded-md ml-2">
                                        Tambah
                                    </button>
                                </div>
                            </div>

                            <!-- Tabel Layanan -->
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Layanan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <template x-for="(item, index) in bookingLayanan" :key="index">
                                            <tr>
                                                <td class="px-6 py-4" x-text="item.nama_layanan"></td>
                                                <td class="px-6 py-4" x-text="`Rp ${item.harga.toLocaleString()}`"></td>
                                                <td class="px-6 py-4" x-text="item.jumlah"></td>
                                                <td class="px-6 py-4" x-text="`Rp ${item.subtotal.toLocaleString()}`"></td>
                                                <td class="px-6 py-4">
                                                    <button @click="removeLayanan(index)" 
                                                        class="text-red-600 hover:text-red-900">
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-gray-50">
                                            <td colspan="3" class="px-6 py-4 text-right font-medium">Total Layanan:</td>
                                            <td class="px-6 py-4 font-bold" x-text="`Rp ${totalLayanan.toLocaleString()}`"></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            :disabled="loading">
                            <span x-text="loading ? 'Menyimpan...' : 'Simpan'"></span>
                        </button>
                        <button type="button" @click="showModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div x-show="showCheckoutModal" class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4">Checkout & Pembayaran</h3>
                    
                    <!-- Detail Tagihan -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span>Total Kamar:</span>
                            <span x-text="formatRupiah(totalKamar)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Layanan:</span>
                            <span x-text="formatRupiah(totalLayanan)"></span>
                        </div>
                        <div class="border-t pt-2">
                            <div class="flex justify-between font-bold">
                                <span>Total:</span>
                                <span x-text="formatRupiah(total_sebelum_diskon)"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Diskon -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium mb-2">Jenis Diskon</label>
                        <div class="flex space-x-4 mb-3">
                            <label class="inline-flex items-center">
                                <input type="radio" x-model="jenis_diskon" value="persen" 
                                    @change="hitungTotal()"
                                    class="text-blue-600">
                                <span class="ml-2">Persentase (%)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" x-model="jenis_diskon" value="nominal" 
                                    @change="hitungTotal()"
                                    class="text-blue-600">
                                <span class="ml-2">Nominal (Rp)</span>
                            </label>
                        </div>
                        <input type="number" x-model="diskon" 
                            @input="hitungTotal()"
                            class="w-full px-3 py-2 border rounded-lg"
                            :placeholder="jenis_diskon === 'persen' ? 'Masukkan persentase' : 'Masukkan nominal'">
                    </div>

                    <!-- Total Akhir -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total Setelah Diskon:</span>
                            <span x-text="formatRupiah(total_setelah_diskon)"></span>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3">
                        <button @click="showCheckoutModal = false" 
                            class="px-4 py-2 border rounded-lg">
                            Batal
                        </button>
                        <button @click="prosesCheckout()" 
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Proses Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('js/booking-handler.js') ?>"></script>
<script>
document.addEventListener('alpine:init', () => {
    console.log('Alpine initialized');
});
</script>
<?= $this->endSection() ?>
