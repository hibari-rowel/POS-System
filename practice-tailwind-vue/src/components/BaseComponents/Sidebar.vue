<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import {useRouter} from "vue-router";
    import _ from 'lodash';
    import { useAuthStore } from "@/stores/auth.js";

    const isOpen = ref(window.innerWidth >= 768);
    const router = useRouter();
    const authStore = useAuthStore();

    onMounted(() => {
        const storedState = localStorage.getItem('is_open');
        if (!_.isEmpty(storedState)) {
            isOpen.value = JSON.parse(storedState);
        }
    });

    const toggleSidebar = () => {
        isOpen.value = !isOpen.value;
        localStorage.setItem('is_open', JSON.stringify(isOpen.value));
    };

    const logout = () => {
        localStorage.removeItem('is_open');
        authStore.logout();
    }
</script>

<template>
    <div class="sidebar-container" :class="isOpen ? 'w-65' : 'w-0 md:w-20'">
        <div class="relative flex justify-between text-2xl font-bold mb-6 h-16 items-center"> 
            <div class="flex px-5 gap-4 overflow-hidden transition-opacity duration-300 ease-in-out md:opacity-100" :class="isOpen ? '' : 'opacity-0'"> 
                <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-full group-hover:shadow-sm bg-gray-200 p-1 overflow-hidden">
                    <img src="/icons/remove.svg" alt="" class="overflow-hidden">
                </div>

                <span class="text-nowrap overflow-hidden"> My App </span>
            </div>

            <button @click="toggleSidebar()" class="absolute cursor-pointer border-r-4 shadow-sm border-transparent transition-all duration-250 ease-in-out -right-8 p-0 h-10 w-8 flex items-center justify-center shrink-0 rounded-r-sm hover:border-blue-500 bg-white">
                <img src="/icons/arrow_right.svg" class="p-0 h-full w-full transition-transform duration-1000 ease-in-out" :class="isOpen ? '-rotate-180' : 'rotate-none'" alt="">
            </button>
        </div>

        <nav class="flex flex-col h-full gap-1 md:gap-2 overflow-x-hidden">
            <router-link :to="'/dashboard'" class="sidebar-link group" active-class="sidebar-link-active">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/dashboard.svg" alt="">
                </div>

                <span class="sidebar-link-label group-hover:text-black">
                    Dashboard
                </span>
            </router-link>

            <router-link :to="'/sales'" class="sidebar-link group" active-class="sidebar-link-active">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/sales.svg" alt="">
                </div>

                <span class="sidebar-link-label group-hover:text-black">
                    Sales
                </span>
            </router-link>

            <router-link :to="'/sales-history'" class="sidebar-link group" active-class="sidebar-link-active">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/history.svg" alt="">
                </div>

                <span class="sidebar-link-label group-hover:text-black">
                    Sales History
                </span>
            </router-link>

            <router-link :to="'/reports'" class="sidebar-link group" active-class="sidebar-link-active">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/report.svg" alt="">
                </div>

                <span class="sidebar-link-label group-hover:text-black">
                    Reports
                </span>
            </router-link>

            <router-link :to="'/product-categories'" class="sidebar-link group" active-class="sidebar-link-active">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/category.svg" alt="">
                </div>

                <span class="sidebar-link-label group-hover:text-black">
                    Categories
                </span>
            </router-link>

            <router-link :to="'/products'" class="sidebar-link group" active-class="sidebar-link-active">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/product.svg" alt="" class="object-cover rounded-full">
                </div>

                <span class="sidebar-link-label group-hover:text-black">
                    Products
                </span>
            </router-link>

            <router-link :to="'/users'" class="sidebar-link group" active-class="sidebar-link-active">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/user.svg" alt="" class="object-cover rounded-full">
                </div>

                <span class="sidebar-link-label group-hover:text-black">
                    Users
                </span>
            </router-link>

            <a href="#" class="mt-auto sidebar-link group" @click="logout()">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/logout.svg" alt="">
                </div>

                <span class="sidebar-link-label group-hover:text-black">
                    Logout
                </span>
            </a>
        </nav>
    </div>
</template>

<style>
</style>