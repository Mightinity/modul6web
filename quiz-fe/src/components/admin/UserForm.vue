<!-- src/components/admin/UserForm.vue -->
<template>
    <div>
        <h2>{{ isEdit ? 'Edit User' : 'Tambah User' }}</h2>
        <form @submit.prevent="submitForm" class="mt-4">
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" id="name" v-model="user.name" class="form-control" placeholder="Masukkan nama user" required />
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" v-model="user.email" class="form-control" placeholder="Masukkan email user" required />
            </div>
            <div class="mb-3" v-if="!isEdit">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" v-model="user.password" class="form-control" placeholder="Masukkan password" required />
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ isEdit ? 'Perbarui' : 'Simpan' }}
            </button>
            <router-link to="/admin/users" class="btn btn-secondary ms-2">
                <i class="fas fa-times"></i> Batal
            </router-link>
        </form>
    </div>
</template>
<script>
    import api from '../../services/api';
    export default {
        name: 'UserForm',
        props: ['id'],
        data() {
            return {
                user: {
                    name: '',
                    email: '',
                    password: '',
                },
            };
        },
        computed: {
            isEdit() {
                return !!this.id;
            },
        },
        methods: {
            fetchUser() {
                api.get(`/users/${this.id}`).then((response) => {
                    this.user = {
                        name: response.data.name,
                        email: response.data.email,
                        password: '', // Password tidak diisi saat edit
                    };
                }).catch((error) => {
                    console.error(error);
                    this.showToast('Gagal mengambil data user.', 'danger');
                });
            },
            submitForm() {
                if (this.isEdit) {
                    // Update user
                    const payload = {
                        name: this.user.name,
                        email: this.user.email,
                    };
                    api.put(`/users/${this.id}`, payload).then(() => {
                        this.showToast('User berhasil diperbarui!', 'success');
                        this.$router.push('/admin/users');
                    }).catch((error) => {
                        console.error(error);
                        this.showToast('Gagal memperbarui user.', 'danger');
                    });
                } else {
                    // Create user
                    api.post('/users', this.user).then(() => {
                        this.showToast('User berhasil ditambahkan!', 'success');
                        this.$router.push('/admin/users');
                    }).catch((error) => {
                        console.error(error);
                        this.showToast('Gagal menambahkan user.', 'danger');
                    });
                }
            },
            showToast(message, type) {
                this.$emit('show-toast', {
                    message,
                    type
                });
            },
        },
        mounted() {
            if (this.isEdit) {
                this.fetchUser();
            }
        },
    };
</script>
<style scoped>
    .form-label {
        font-weight: bold;
    }
</style>