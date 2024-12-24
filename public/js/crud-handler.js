function crudHandler(config) {
    return {
        showModal: false,
        loading: false,
        modalTitle: '',
        formMode: 'create',
        formData: {...config.defaultData},

        // Initialize modal for create
        create() {
            this.formMode = 'create';
            this.modalTitle = config.createTitle;
            this.formData = {...config.defaultData};
            this.showModal = true;
        },

        edit(data) {
            this.formMode = 'edit';
            this.modalTitle = config.editTitle;
            this.formData = {...data};
            this.showModal = true;
        },

        // Close modal
        closeModal() {
            this.showModal = false;
            this.formData = {...config.defaultData};
        },

        // Save data
        async save() {
            this.loading = true;
            try {
                const response = await fetch(this.formMode === 'create' 
                    ? `${config.baseUrl}/store`
                    : `${config.baseUrl}/update/${this.formData.id_kamar}`, {
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
                    this.errors = data.messages || { error: 'Gagal menyimpan data' };
                }
            } catch (error) {
                console.error('Error:', error);
                this.errors = { error: 'Terjadi kesalahan sistem' };
            } finally {
                this.loading = false;
            }
        }
    }
}
