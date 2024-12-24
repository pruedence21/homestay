function bookingHandler() {
    const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
    console.log('Base URL:', baseUrl);

    return {
        showModal: false,
        showCheckoutModal: false, // Tambahkan ini
        loading: false,
        modalTitle: '',
        formMode: 'create',
        kamarTersedia: [],
        totalKamar: 0, // Tambahkan ini
        formData: {
            id_kamar: '',
            nama_tamu: '',
            email: '',
            no_telp: '',
            checkin: '',
            checkout: '',
            jumlah_tamu: 1,
            total_harga: 0,
            status: 'pending'
        },
        selectedLayanan: '',
        jumlahLayanan: 1,
        layananList: [],
        bookingLayanan: [],
        totalLayanan: 0,
        diskon: 0,
        jenis_diskon: 'persen',
        total_sebelum_diskon: 0,
        total_setelah_diskon: 0,

        async init() {
            console.log('Booking handler initialized');
            await this.loadLayananList(); // Make sure this runs first
            console.log('Loaded layanan:', this.layananList);
            this.loadKamarTersedia();
            if (this.formData.id_booking) {
                await this.loadBookingLayanan();
            }
        },

        async loadKamarTersedia() {
            try {
                const response = await fetch(baseUrl + '/admin/kamar/tersedia');
                this.kamarTersedia = await response.json();
                console.log('Kamar tersedia:', this.kamarTersedia);
            } catch (error) {
                console.error('Error loading kamar:', error);
            }
        },

        async loadLayananList() {
            try {
                // Update the URL to match your backend route
                const response = await fetch(`${baseUrl}/admin/layanan/list`); // Change this line
                const data = await response.json();
                this.layananList = data;
                console.log('Layanan loaded:', this.layananList);
            } catch (error) {
                console.error('Error loading layanan:', error);
            }
        },

        async loadBookingLayanan() {
            if (!this.formData.id_booking) return;
            try {
                const response = await fetch(`${baseUrl}/admin/booking/${this.formData.id_booking}/layanan`);
                const data = await response.json();
                this.bookingLayanan = data;
                this.calculateTotalLayanan();
            } catch (error) {
                console.error('Error loading booking layanan:', error);
            }
        },

        create() {
            console.log('Create method called');
            this.formMode = 'create';
            this.modalTitle = 'Tambah Booking';
            this.resetForm();
            this.showModal = true;
            console.log('LayananList:', this.layananList); // Debug layanan data
        },

        edit(booking) {
            this.formMode = 'edit';
            this.modalTitle = 'Edit Booking';
            this.formData = {...booking};
            this.showModal = true;
        },

        async checkAvailability() {
            if (!this.formData.id_kamar || !this.formData.checkin || !this.formData.checkout) return;

            this.loading = true;
            try {
                // Remove trailing slash from baseUrl if exists
                const cleanBaseUrl = baseUrl.replace(/\/$/, '');
                
                const response = await fetch(`${cleanBaseUrl}/admin/booking/check-availability`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id_kamar: this.formData.id_kamar,
                        checkin: this.formatDate(this.formData.checkin), // Format date properly
                        checkout: this.formatDate(this.formData.checkout) // Format date properly
                    })
                });
                
                const data = await response.json();
                
                if (!data.success) {
                    alert(data.message || 'Kamar tidak tersedia untuk tanggal yang dipilih');
                    this.formData.id_kamar = '';
                }
            } catch (error) {
                console.error('Error checking availability:', error);
                alert('Terjadi kesalahan saat memeriksa ketersediaan kamar');
            } finally {
                this.loading = false;
            }
        },

        calculateTotalHarga() {
            const kamar = this.kamarTersedia.find(k => k.id_kamar == this.formData.id_kamar);
            if (!kamar) return;

            const checkin = new Date(this.formData.checkin);
            const checkout = new Date(this.formData.checkout);
            const days = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
            
            this.formData.total_harga = kamar.harga * days;
        },

        async save() {
            this.loading = true;
            try {
                const url = `${baseUrl}/admin/booking/store`;
                
                const bookingData = {
                    ...this.formData,
                    layanan: this.bookingLayanan,
                    total_sebelum_diskon: this.total_sebelum_diskon,
                    total_setelah_diskon: this.total_setelah_diskon,
                    diskon: this.diskon,
                    jenis_diskon: this.jenis_diskon
                };

                console.log('Sending booking data:', bookingData);

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(bookingData)
                });

                const data = await response.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.messages ? Object.values(data.messages).join('\n') : data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan booking');
            } finally {
                this.loading = false;
            }
        },

        canAddLayanan() {
            return this.selectedLayanan && 
                   this.jumlahLayanan > 0;
        },

        canSaveBooking() {
            return this.formData.id_kamar &&
                   this.formData.nama_tamu &&
                   this.formData.email &&
                   this.formData.no_telp &&
                   this.formData.checkin &&
                   this.formData.checkout &&
                   this.formData.jumlah_tamu > 0;
        },

        addLayanan() {
            if (!this.canAddLayanan()) {
                alert('Pilih layanan dan jumlah terlebih dahulu');
                return;
            }
            
            const layanan = this.layananList.find(l => l.id_layanan == this.selectedLayanan);
            if (!layanan) return;

            // Check if service already exists
            const existingIndex = this.bookingLayanan.findIndex(
                item => item.id_layanan === layanan.id_layanan
            );

            if (existingIndex >= 0) {
                // Update existing service
                this.bookingLayanan[existingIndex].jumlah += this.jumlahLayanan;
                this.bookingLayanan[existingIndex].subtotal = 
                    this.bookingLayanan[existingIndex].jumlah * layanan.harga;
            } else {
                // Add new service
                this.bookingLayanan.push({
                    id_layanan: layanan.id_layanan,
                    nama_layanan: layanan.nama_layanan,
                    harga: layanan.harga,
                    jumlah: this.jumlahLayanan,
                    subtotal: layanan.harga * this.jumlahLayanan
                });
            }

            this.calculateTotalLayanan();
            this.resetLayananForm();
        },

        removeLayanan(index) {
            this.bookingLayanan.splice(index, 1);
            this.calculateTotalLayanan();
        },

        calculateTotalLayanan() {
            this.totalLayanan = this.bookingLayanan.reduce((sum, item) => sum + item.subtotal, 0);
            this.formData.total_harga = this.totalLayanan;
        },

        resetLayananForm() {
            this.selectedLayanan = '';
            this.jumlahLayanan = 1;
        },

        resetForm() {
            this.formData = {
                id_kamar: '',
                nama_tamu: '',
                email: '',
                no_telp: '',
                checkin: '',
                checkout: '',
                jumlah_tamu: 1,
                total_harga: 0,
                status: 'pending'
            };
        },

        async updateStatus(id, status) {
            try {
                const response = await fetch(`${baseUrl}/admin/booking/status/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ status })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem');
            }
        },

        hitungTotal() {
            // Hitung total kamar
            const checkin = new Date(this.formData.checkin);
            const checkout = new Date(this.formData.checkout);
            const jumlahHari = Math.ceil((checkout - checkin) / (1000 * 60 * 60 * 24));
            const hargaKamar = this.kamarTersedia.find(k => k.id_kamar == this.formData.id_kamar)?.harga || 0;
            this.totalKamar = hargaKamar * jumlahHari; // Set totalKamar

            // Hitung total layanan
            const totalLayanan = this.bookingLayanan.reduce((sum, item) => sum + item.subtotal, 0);

            // Total sebelum diskon
            this.total_sebelum_diskon = this.totalKamar + totalLayanan;

            // Hitung diskon
            let nilaiDiskon = 0;
            if (this.jenis_diskon === 'persen') {
                nilaiDiskon = (this.total_sebelum_diskon * this.diskon) / 100;
            } else {
                nilaiDiskon = this.diskon;
            }

            // Total setelah diskon
            this.total_setelah_diskon = this.total_sebelum_diskon - nilaiDiskon;
            this.formData.total_harga = this.total_setelah_diskon;
        },

        // Add helper function for date formatting
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toISOString().split('T')[0];
        },

        // Tambahkan helper function untuk format rupiah
        formatRupiah(amount) {
            return `Rp ${amount.toLocaleString('id-ID')}`;
        },

        async prosesCheckout() {
            try {
                this.loading = true;
                // Lakukan proses checkout
                await this.save();
                this.showCheckoutModal = false;
                window.location.reload();
            } catch (error) {
                console.error('Checkout error:', error);
                alert('Terjadi kesalahan saat checkout');
            } finally {
                this.loading = false;
            }
        }
    }
}

// Initialize Alpine.js component
document.addEventListener('alpine:init', () => {
    Alpine.data('bookingHandler', bookingHandler);
});
