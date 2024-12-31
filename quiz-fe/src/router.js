// src/router.js
import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue'; // Halaman Beranda Baru
import UsersList from './components/admin/UsersList.vue';
import UserForm from './components/admin/UserForm.vue';
import QuizzesList from './components/admin/QuizzesList.vue';
import QuizForm from './components/admin/QuizForm.vue';
import QuizView from './components/QuizView.vue'; // Halaman Quiz Baru
import NotFound from './components/NotFound.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/admin',
        name: 'Admin',
        component: () => import('./components/admin/AdminLayout.vue'), // Layout Admin
        children: [
            {
                path: 'users',
                name: 'UsersList',
                component: UsersList,
            },
            {
                path: 'users/create',
                name: 'CreateUser',
                component: UserForm,
            },
            {
                path: 'users/edit/:id',
                name: 'EditUser',
                component: UserForm,
                props: true,
            },
            {
                path: 'quizzes',
                name: 'QuizzesList',
                component: QuizzesList,
            },
            {
                path: 'quizzes/create',
                name: 'CreateQuiz',
                component: QuizForm,
            },
            {
                path: 'quizzes/edit/:id',
                name: 'EditQuiz',
                component: QuizForm,
                props: true,
            },
        ],
    },
    {
        path: '/quiz/:code',
        name: 'QuizView',
        component: QuizView,
        props: true,
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound,
    },
    // Tambahkan rute lain jika diperlukan
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
