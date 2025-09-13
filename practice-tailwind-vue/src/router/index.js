import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from "@/stores/auth.js";

import LoginView from '@/components/ModuleComponents/Auth/LoginView.vue';
import PurchaseView from '@/components/ModuleComponents/Purchases/PurchaseView.vue';
import DashboardView from '@/components/ModuleComponents/Dashboard/DashboardView.vue';
import PurchaseHistoryListView from '@/components/ModuleComponents/PurchaseHistory/ListView.vue';

import ProductsListView from '@/components/ModuleComponents/Products/ListView.vue';
import ProductsCreateView from '@/components/ModuleComponents/Products/CreateView.vue';
import ProductsShowView from '@/components/ModuleComponents/Products/ShowView.vue';
import ProductsEditView from '@/components/ModuleComponents/Products/EditView.vue';

import ProductCategoriesListView from '@/components/ModuleComponents/ProductCategories/ListView.vue';
import ProductCategoriesCreateView from '@/components/ModuleComponents/ProductCategories/CreateView.vue';
import ProductCategoriesShowView from '@/components/ModuleComponents/ProductCategories/ShowView.vue';
import ProductCategoriesEditView from '@/components/ModuleComponents/ProductCategories/EditView.vue';

import ReportsListView from '@/components/ModuleComponents/Reports/ListView.vue';
import ReportsCreateView from '@/components/ModuleComponents/Reports/CreateView.vue';
import ReportsShowView from '@/components/ModuleComponents/Reports/ShowView.vue';

import UsersListView from '@/components/ModuleComponents/Users/ListView.vue';
import UsersCreateView from '@/components/ModuleComponents/Users/CreateView.vue';
import UsersShowView from '@/components/ModuleComponents/Users/ShowView.vue';
import UsersEditView from '@/components/ModuleComponents/Users/EditView.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'login',
            component: LoginView,
            meta: { requiresAuth: false, allowedUsers: ['admin', 'staff'] },
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: DashboardView,
            meta: { requiresAuth: true, allowedUsers: ['admin', 'staff'] },
        },
        {
            path: '/purchases',
            name: 'purchases',
            component: PurchaseView,
            meta: { requiresAuth: true, allowedUsers: ['admin', 'staff'] },
        },
        {
            path: '/purchase-history',
            name: 'purchase-history',
            component: PurchaseHistoryListView,
            meta: { requiresAuth: true, allowedUsers: ['admin', 'staff'] },
        },
        {
            path: '/products',
            meta: { requiresAuth: true, allowedUsers: ['admin',] },
            children: [
                { path: '', component: ProductsListView },
                { path: 'create', component: ProductsCreateView },
                { path: 'show/:id', component: ProductsShowView },
                { path: 'edit/:id', component: ProductsEditView },
            ],
        },
        {
            path: '/product-categories',
            meta: { requiresAuth: true, allowedUsers: ['admin',] },
            children: [
                { path: '', component: ProductCategoriesListView },
                { path: 'create', component: ProductCategoriesCreateView },
                { path: 'show/:id', component: ProductCategoriesShowView },
                { path: 'edit/:id', component: ProductCategoriesEditView },
            ],
        },
        {
            path: '/reports',
            meta: { requiresAuth: true, allowedUsers: ['admin', 'staff'] },
            children: [
                { path: '', component: ReportsListView },
                { path: 'create', component: ReportsCreateView },
                { path: 'show/:id', component: ReportsShowView },
            ],
        },
        {
            path: '/users',
            meta: { requiresAuth: true, allowedUsers: ['admin',] },
            children: [
                { path: '', component: UsersListView },
                { path: 'create', component: UsersCreateView },
                { path: 'show/:id', component: UsersShowView },
                { path: 'edit/:id', component: UsersEditView },
            ],
        },
    ]
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    await authStore.fetchUser();

    if (!authStore.isAuthenticated && to.meta.requiresAuth) {
        return next('/');
    }

    if (authStore.isAuthenticated && !to.meta.requiresAuth) {
        return next('/dashboard');
    }

    next();
});

export default router;