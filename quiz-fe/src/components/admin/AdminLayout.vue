<!-- src/components/admin/AdminLayout.vue -->
<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <router-link class="navbar-brand" to="/admin">
                    <i class="fas fa-tachometer-alt"></i> Admin Dashboard
                </router-link>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="adminNavbar">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <router-link class="nav-link" to="/admin/users">
                                <i class="fas fa-users"></i> Users
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link class="nav-link" to="/admin/quizzes">
                                <i class="fas fa-clipboard-list"></i> Quizzes
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
    export default {
        name: 'AdminLayout',
    };
</script>
<style scoped>
    .nav-link {
        display: flex;
        align-items: center;
    }

    .nav-link i {
        margin-right: 5px;
    }

    .navbar-brand i {
        margin-right: 5px;
    }
</style>