function layananHandler() {
    return {
        showModal: false,
        loading: false,
        modalTitle: '',
        formMode: 'create',
        formData: {
            nama_layanan: '',
            kategori: '',
            harga: '',
            deskripsi: ''
        },

        init() {
            console.log('Layanan handler initialized');
        },

        create() {
            this.formMode = 'create';
            this.modalTitle = 'Tambah Layanan';
            this.resetForm();
            this.showModal = true;
        },

        edit(layanan) {
            this.formMode = 'edit';
            this.modalTitle = 'Edit Layanan';
            this.formData = {...layanan};
            this.showModal = true;
        },

        async save() {
            this.loading = true;
            try {
                const url = this.formMode === 'create' 
                    ? `${baseUrl}/admin/layanan/store`  // Changed from simpan to store
                    : `${baseUrl}/admin/layanan/ubah/${this.formData.id_layanan}`;

                console.log('Saving data:', this.formData);  // Debug log

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();
                console.log('Response:', data);  // Debug log

                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.messages ? Object.values(data.messages).join('\n') : data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem');
            } finally {
                this.loading = false;
            }
        },

        async deleteData(id) {
            if (confirm('Yakin ingin menghapus?')) {
                try {
                    const response = await fetch(`${baseUrl}/admin/layanan/hapus/${id}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        window.location.reload();
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem');
                }
            }
        },

        resetForm() {
            this.formData = {
                nama_layanan: '',
                kategori: '',
                harga: '',
                deskripsi: ''
            };
        }
    }
}
