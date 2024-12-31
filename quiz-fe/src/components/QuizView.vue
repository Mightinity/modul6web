<!-- src/components/QuizView.vue -->
<template>
    <div class="quiz-view">
        <div class="container mt-5">
            <div v-if="loading" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div v-else>
                <h2 class="mb-4">{{ quiz.title }}</h2>
                <form @submit.prevent="submitQuiz">
                    <div v-for="(question, index) in quiz.questions" :key="question.id" class="mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ index + 1 }}. {{ question.question_text }}
                                </h5>
                                <div v-for="option in ['a', 'b', 'c', 'd', 'e']" :key="option" class="form-check">
                                    <input class="form-check-input" type="radio" :name="'question-' + index" :id="'question-' + index + '-' + option" :value="option" v-model="answers[index]" required />
                                    <label class="form-check-label" :for="'question-' + index + '-' + option">
                                        {{ option.toUpperCase() }}. {{ question.options[option] }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mb-3">
                        <i class="fas fa-check-circle"></i> Selesai Mengerjakan </button>
                </form>
                <div v-if="result !== null" class="result-card">
                    <div class="card">
                        <div class="card-body">
                            <h3>Hasil Quiz</h3>
                            <p> Anda mendapatkan <strong>{{ result.score }}</strong> dari <strong>{{ quiz.questions.length }}</strong> soal. </p>
                            <button class="btn btn-primary" @click="resetQuiz">
                                <i class="fas fa-redo"></i> Kerjakan Ulang </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import api from '../services/api';
    export default {
        name: 'QuizView',
        props: ['code'],
        data() {
            return {
                quiz: {
                    id: null,
                    code: '',
                    title: '',
                    questions: [],
                },
                loading: true,
                answers: [],
                result: null,
            };
        },
        methods: {
            fetchQuizByCode() {
                api.get(`/quizzes/code/${this.code}`).then((response) => {
                    this.quiz = response.data;
                    this.answers = Array(this.quiz.questions.length).fill('');
                }).catch((error) => {
                    console.error(error);
                    alert('Kode quiz tidak valid atau tidak ditemukan!');
                    this.$router.push('/');
                }).finally(() => {
                    this.loading = false;
                });
            },
            submitQuiz() {
                // Hitung skor
                let score = 0;
                this.quiz.questions.forEach((question, index) => {
                    if (this.answers[index] === question.correct_answer) {
                        score += 1;
                    }
                });
                this.result = {
                    score
                };
                this.showToast(`Anda mendapatkan ${score} dari ${this.quiz.questions.length} soal.`, 'success');
                // Anda juga bisa mengirim hasil ke backend jika diperlukan
            },
            resetQuiz() {
                this.answers = Array(this.quiz.questions.length).fill('');
                this.result = null;
            },
            showToast(message, type) {
                this.$emit('show-toast', {
                    message,
                    type
                });
            },
        },
        mounted() {
            this.fetchQuizByCode();
        },
    };
</script>
<style scoped>
    .quiz-view {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 10px;
    }

    .result-card {
        margin-top: 30px;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
    }
</style>