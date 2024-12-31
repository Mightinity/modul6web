<!-- src/components/admin/QuizzesList.vue -->
<template>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Daftar Quizzes</h2>
            <router-link to="/admin/quizzes/create" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Tambah Quiz
            </router-link>
        </div>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Kode Quiz</th>
                    <th>Judul</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="quiz in quizzes" :key="quiz.id">
                    <td>{{ quiz.id }}</td>
                    <td>{{ quiz.code }}</td>
                    <td>{{ quiz.title }}</td>
                    <td>
                        <router-link :to="`/admin/quizzes/edit/${quiz.id}`" class="btn btn-sm btn-primary me-2">
                            <i class="fas fa-edit"></i> Edit
                        </router-link>
                        <button @click="openDeleteModal(quiz)" class="btn btn-sm btn-danger">
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
                    <div class="modal-body"> Apakah Anda yakin ingin menghapus quiz <strong>{{ selectedQuiz.title }}</strong>? </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" @click="deleteQuiz">Hapus</button>
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
        name: 'QuizzesList',
        data() {
            return {
                quizzes: [],
                selectedQuiz: {},
                deleteModal: null,
            };
        },
        methods: {
            fetchQuizzes() {
                api.get('/quizzes').then((response) => {
                    this.quizzes = response.data;
                }).catch((error) => {
                    console.error(error);
                });
            },
            openDeleteModal(quiz) {
                this.selectedQuiz = quiz;
                this.deleteModal.show();
            },
            deleteQuiz() {
                api.delete(`/quizzes/${this.selectedQuiz.id}`).then(() => {
                    this.fetchQuizzes();
                    this.deleteModal.hide();
                    this.showToast('Quiz berhasil dihapus!', 'success');
                }).catch((error) => {
                    console.error(error);
                    this.showToast('Gagal menghapus quiz.', 'danger');
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
            this.fetchQuizzes();
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