import { createRouter, createWebHistory } from 'vue-router';

import LoginView from '@/components/ModuleComponents/Auth/LoginView.vue';
import RegisterView from '@/components/ModuleComponents/Auth/RegisterView.vue';
import PurchaseView from '@/components/ModuleComponents/Purchases/PurchaseView.vue';
import AnalyticsView from '@/components/ModuleComponents/Analytics/AnalyticsView.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'login',
            component: LoginView,
            meta: { requiresAuth: false },
        },
        {
            path: '/register',
            name: 'register',
            component: RegisterView,
            meta: { requiresAuth: false },
        },
        {
            path: '/purchases',
            name: 'purchases',
            component: PurchaseView,
            meta: { requiresAuth: false },
        },
        {
            path: '/analytics',
            name: 'analytics',
            component: AnalyticsView,
            meta: { requiresAuth: false },
        },
    ]
});

export default router;