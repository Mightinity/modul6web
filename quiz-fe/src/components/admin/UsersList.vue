<!-- src/components/admin/UsersList.vue -->
<template>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Daftar Users</h2>
            <router-link to="/admin/users/create" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Tambah User
            </router-link>
        </div>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                        <router-link :to="`/admin/users/edit/${user.id}`" class="btn btn-sm btn-primary me-2">
                            <i class="fas fa-edit"></i> Edit
                        </router-link>
                        <button @click="openDeleteModal(user)" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash-alt"></i> Hapus </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Modal Konfirmasi Hapus -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> Apakah Anda yakin ingin menghapus user <strong>{{ selectedUser.name }}</strong>? </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" @click="deleteUser">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import api from '../../services/api';
    import {
        Modal
    } from 'bootstrap';
    export default {
        name: 'UsersList',
        data() {
            return {
                users: [],
                selectedUser: {},
                deleteModal: null,
            };
        },
        methods: {
            fetchUsers() {
                api.get('/users').then((response) => {
                    this.users = response.data;
                }).catch((error) => {
                    console.error(error);
                });
            },
            openDeleteModal(user) {
                this.selectedUser = user;
                this.deleteModal.show();
            },
            deleteUser() {
                api.delete(`/users/${this.selectedUser.id}`).then(() => {
                    this.fetchUsers();
                    this.deleteModal.hide();
                    this.showToast('User berhasil dihapus!', 'success');
                }).catch((error) => {
                    console.error(error);
                    this.showToast('Gagal menghapus user.', 'danger');
                });
            },
            showToast(message, type) {
                this.$emit('show-toast', {
                    message,
                    type
                });
            },
        },
        mounted() {
            this.fetchUsers();
            // Inisialisasi modal
            const modalElement = document.getElementById('deleteModal');
            this.deleteModal = new Modal(modalElement);
        },
    };
</script>
<style scoped>
    .table thead th {
        vertical-align: middle;
    }
</style>