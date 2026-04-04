import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../views/HomeView.vue')
    },
    {
        path: '/intranet',
        component: () => import('../layouts/IntranetLayout.vue'),
        children: [
            {
                path: '',
                redirect: '/intranet/actividades'
            },
            {
                path: 'actividades',
                component: () => import('../views/intranet/ActividadesView.vue')
            },
            {
                path: 'actividades/:id',
                component: () => import('../views/intranet/ActivityDetailView.vue')
            },
            {
                path: 'voluntarios',
                component: () => import('../views/intranet/VoluntariosView.vue')
            }
        ]
    }
]

export const router = createRouter({
    history: createWebHistory(),
    routes
})