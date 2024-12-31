<!-- src/components/admin/QuizForm.vue -->
<template>
    <div>
        <h2>{{ isEdit ? 'Edit Quiz' : 'Tambah Quiz' }}</h2>
        <form @submit.prevent="submitForm" class="mt-4">
            <div class="mb-3">
                <label for="title" class="form-label">Judul Quiz</label>
                <input type="text" id="title" v-model="quiz.title" class="form-control" placeholder="Masukkan judul quiz" required />
            </div>
            <div class="mb-3">
                <label class="form-label">Pertanyaan</label>
                <div v-for="(question, index) in quiz.questions" :key="index" class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5>Pertanyaan {{ index + 1 }}</h5>
                            <button type="button" class="btn btn-danger btn-sm" @click="removeQuestion(index)">
                                <i class="fas fa-trash-alt"></i> Hapus </button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teks Pertanyaan</label>
                            <textarea v-model="question.question_text" class="form-control" placeholder="Masukkan teks pertanyaan" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilihan Jawaban</label>
                            <div v-for="option in ['a', 'b', 'c', 'd', 'e']" :key="option" class="mb-2">
                                <input type="text" v-model="question.options[option]" :placeholder="`Pilihan ${option.toUpperCase()}`" class="form-control" required />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jawaban Benar</label>
                            <select v-model="question.correct_answer" class="form-select" required>
                                <option value="" disabled>Pilih Jawaban Benar</option>
                                <option v-for="option in ['a', 'b', 'c', 'd', 'e']" :key="option" :value="option">
                                    {{ option.toUpperCase() }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" @click="addQuestion">
                    <i class="fas fa-plus-circle"></i> Tambah Pertanyaan </button>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{ isEdit ? 'Perbarui Quiz' : 'Simpan Quiz' }}
            </button>
            <router-link to="/admin/quizzes" class="btn btn-secondary ms-2">
                <i class="fas fa-times"></i> Batal
            </router-link>
        </form>
    </div>
</template>
<script>
    import api from '../../services/api';
    export default {
        name: 'QuizForm',
        props: ['id'],
        data() {
            return {
                quiz: {
                    title: '',
                    questions: [],
                },
            };
        },
        computed: {
            isEdit() {
                return !!this.id;
            },
        },
        methods: {
            fetchQuiz() {
                api.get(`/quizzes/${this.id}`).then((response) => {
                    this.quiz.title = response.data.title;
                    this.quiz.questions = response.data.questions.map((q) => ({
                        question_text: q.question_text,
                        options: q.options,
                        correct_answer: q.correct_answer,
                    }));
                }).catch((error) => {
                    console.error(error);
                    this.showToast('Gagal mengambil data quiz.', 'danger');
                });
            },
            addQuestion() {
                this.quiz.questions.push({
                    question_text: '',
                    options: {
                        a: '',
                        b: '',
                        c: '',
                        d: '',
                        e: ''
                    },
                    correct_answer: '',
                });
            },
            removeQuestion(index) {
                this.quiz.questions.splice(index, 1);
            },
            submitForm() {
                if (this.isEdit) {
                    // Update quiz title
                    const payload = {
                        title: this.quiz.title,
                    };
                    api.put(`/quizzes/${this.id}`, payload).then(() => {
                        // Menghapus semua pertanyaan lama
                        api.get(`/quizzes/${this.id}`).then((response) => {
                            const existingQuestions = response.data.questions;
                            existingQuestions.forEach((q) => {
                                api.delete(`/questions/${q.id}`).catch((error) => console.error(error));
                            });
                        }).then(() => {
                            // Menambahkan pertanyaan baru
                            this.quiz.questions.forEach((q) => {
                                api.post(`/quizzes/${this.id}/questions`, q).catch((error) => console.error(error));
                            });
                        }).then(() => {
                            this.showToast('Quiz berhasil diperbarui!', 'success');
                            this.$router.push('/admin/quizzes');
                        });
                    }).catch((error) => {
                        console.error(error);
                        this.showToast('Gagal memperbarui quiz.', 'danger');
                    });
                } else {
                    // Create quiz
                    api.post('/quizzes', this.quiz).then(() => {
                        this.showToast('Quiz berhasil ditambahkan!', 'success');
                        this.$router.push('/admin/quizzes');
                    }).catch((error) => {
                        console.error(error);
                        this.showToast('Gagal menambahkan quiz.', 'danger');
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
                this.fetchQuiz();
            } else {
                // Tambahkan satu pertanyaan default saat membuat quiz baru
                this.addQuestion();
            }
        },
    };
</script>
<style scoped>
    .form-label {
        font-weight: bold;
    }

    .card {
        border: 1px solid #dee2e6;
    }
</style>