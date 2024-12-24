function bookingHandler() {
    const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
    console.log('Base URL:', baseUrl);

    return {
        showModal: false,
        loading: false,
        modalTitle: '',
        formMode: 'create',
        kamarTersedia: [],
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
            this.loadKamarTersedia();
            await this.loadLayananList();
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
                const response = await fetch(`${baseUrl}/admin/layanan`);
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
                const response = await fetch(`${baseUrl}/admin/booking/check-availability`, { // Corrected URL
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        id_kamar: this.formData.id_kamar,
                        checkin: this.formData.checkin,
                        checkout: this.formData.checkout
                    })
                });
                const data = await response.json();
                if (!data.available) {
                    alert('Kamar tidak tersedia untuk tanggal yang dipilih');
                    this.formData.id_kamar = '';
                }
            } catch (error) {
                console.error('Error:', error);
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
                // Validate required fields first
                if (!this.formData.id_kamar || !this.formData.nama_tamu || 
                    !this.formData.email || !this.formData.no_telp ||
                    !this.formData.checkin || !this.formData.checkout) {
                    alert('Semua field harus diisi');
                    return;
                }

                // Remove any trailing slash from baseUrl
                const cleanBaseUrl = baseUrl.replace(/\/$/, '');
                
                const url = this.formMode === 'create' 
                    ? `${cleanBaseUrl}/admin/booking/store`
                    : `${cleanBaseUrl}/admin/booking/update/${this.formData.id_booking}`;

                // Calculate total before saving
                this.hitungTotal();

                // Format dates properly
                const payload = {
                    ...this.formData,
                    checkin: this.formatDate(this.formData.checkin),
                    checkout: this.formatDate(this.formData.checkout),
                    layanan: this.bookingLayanan,
                    total_sebelum_diskon: this.total_sebelum_diskon,
                    total_setelah_diskon: this.total_setelah_diskon,
                    diskon: this.diskon,
                    jenis_diskon: this.jenis_diskon
                };

                console.log('Payload:', payload);

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.messages ? Object.values(data.messages).join('\n') : data.message);
                }

                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Save Error:', error);
                alert(error.message || 'Terjadi kesalahan sistem');
            } finally {
                this.loading = false;
            }
        },

        addLayanan() {
            if (!this.selectedLayanan || this.jumlahLayanan < 1) return;
            
            const layanan = this.layananList.find(l => l.id_layanan == this.selectedLayanan);
            if (!layanan) return;

            const subtotal = layanan.harga * this.jumlahLayanan;
            this.bookingLayanan.push({
                id_layanan: layanan.id_layanan,
                nama_layanan: layanan.nama_layanan,
                harga: layanan.harga,
                jumlah: this.jumlahLayanan,
                subtotal: subtotal
            });

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
            const totalKamar = hargaKamar * jumlahHari;

            // Hitung total layanan
            const totalLayanan = this.bookingLayanan.reduce((sum, item) => sum + item.subtotal, 0);

            // Total sebelum diskon
            this.total_sebelum_diskon = totalKamar + totalLayanan;

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
        }
    }
}

// Initialize Alpine.js component
document.addEventListener('alpine:init', () => {
    Alpine.data('bookingHandler', bookingHandler);
});
