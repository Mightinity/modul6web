<!-- src/components/NotFound.vue -->
<template>
    <div class="not-found">
        <div class="container mt-5 text-center">
            <h1>404</h1>
            <p>Halaman yang Anda cari tidak ditemukan.</p>
            <router-link to="/" class="btn btn-primary">Kembali ke Beranda</router-link>
        </div>
    </div>
</template>

<script>
export default {
    name: 'NotFound',
};
</script>

<style scoped>
.not-found {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}
</style>
