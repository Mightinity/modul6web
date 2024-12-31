<!-- src/components/Home.vue -->
<template>
    <div class="home">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1 class="display-4 mb-4">Selamat Datang di Quiz Test Website</h1>
                    <p class="lead mb-4"> Masukkan kode quiz Anda di bawah ini untuk memulai ujian. </p>
                    <form @submit.prevent="submitCode">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Masukkan Kode Quiz (8 huruf)" v-model="code" required maxlength="8" />
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-play-circle"></i> Mulai Quiz </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'Home',
        data() {
            return {
                code: '',
            };
        },
        methods: {
            submitCode() {
                const trimmedCode = this.code.trim().toUpperCase();
                if (trimmedCode.length !== 8) {
                    alert('Kode quiz harus terdiri dari 8 huruf!');
                    return;
                }
                // Redirect ke halaman QuizView dengan kode
                this.$router.push({
                    name: 'QuizView',
                    params: {
                        code: trimmedCode
                    }
                });
            },
        },
    };
</script>
<style scoped>
    .home {
        background-color: #ffffff;
        padding: 50px 0;
    }

    .display-4 {
        font-weight: bold;
    }

    .lead {
        font-size: 1.25rem;
    }

    .img-fluid {
        max-height: 300px;
    }
</style>