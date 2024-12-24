// crud-handler.js
function crudHandler(config) {
    return {
        showModal: false,
        loading: false,
        modalTitle: '',
        formMode: 'create',
        formData: {...config.defaultData},
        errors: {},

        create() {
            this.formMode = 'create';
            this.modalTitle = config.createTitle;
            this.resetForm();
            this.showModal = true;
        },

        edit(data) {
            this.formMode = 'edit';
            this.modalTitle = config.editTitle;
            this.formData = {...data};
            this.showModal = true;
        },

        async save() {
            this.loading = true;
            this.errors = {};
            
            try {
                const url = this.formMode === 'create' 
                    ? `${config.baseUrl}/store`
                    : `${config.baseUrl}/update/${this.formData[config.primaryKey]}`;

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    this.errors = data.messages || { error: data.message || 'Gagal menyimpan data' };
                }
            } catch (error) {
                console.error('Error:', error);
                this.errors = { error: 'Terjadi kesalahan sistem' };
            } finally {
                this.loading = false;
            }
        },

        async deleteData(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) return;

            try {
                const response = await fetch(`${config.baseUrl}/delete/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal menghapus data');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem');
            }
        },

        resetForm() {
            this.formData = {...config.defaultData};
            this.errors = {};
        },

        closeModal() {
            this.showModal = false;
            this.resetForm();
        }
    }
}
