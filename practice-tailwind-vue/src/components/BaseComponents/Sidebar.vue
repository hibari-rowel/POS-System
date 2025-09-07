<script setup lang="ts">
    import { ref } from 'vue';

    const isOpen = ref(true);
</script>

<template>
    <div class="flex flex-col shadow-sm px-0 py-3 mr-5 rounded-sm transition-all duration-700 ease-in-out bg-gray-50 fixed md:static top-0 left-0 h-full z-50" 
        :class="isOpen ? 'w-64' : 'w-0 md:w-20'">
        <div class="relative flex justify-between text-2xl font-bold mb-6 h-16 items-center"> 
            <div class="flex px-5 gap-4 overflow-hidden transition-opacity duration-300 ease-in-out md:opacity-100" :class="isOpen ? '' : 'opacity-0'"> 
                <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-full group-hover:shadow-sm bg-gray-200 p-1 overflow-hidden">
                    <img src="/icons/remove.svg" alt="" class="overflow-hidden">
                </div>

                <span class="text-nowrap overflow-hidden"> My App </span>
            </div>

            <button @click="isOpen = !isOpen" class="absolute cursor-pointer border-r-4 border-transparent transition-all duration-250 ease-in-out -right-8 md:-right-6 p-0 h-10 w-10 flex items-center justify-center shrink-0 rounded-r-sm hover:border-blue-500 bg-gray-50" >
                <img src="/icons/arrow_right.svg" class="p-0 h-full w-full transition-transform duration-1000 ease-in-out" :class="isOpen ? '-rotate-180' : 'rotate-none'" alt="">
            </button>
        </div>

        <nav class="flex flex-col h-full gap-1 overflow-hidden">
            <a href="#" class="sidebar-link group">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/remove.svg" alt="">
                </div>

                <span class="sidebar-link-label">
                    Analytics
                </span>
            </a>

            <a href="#" class="sidebar-link group">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/remove.svg" alt="">
                </div>

                <span class="sidebar-link-label">
                    Purchases
                </span>
            </a>

            <a href="#" class="sidebar-link group">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/remove.svg" alt="">
                </div>

                <span class="sidebar-link-label">
                    Purchase History
                </span>
            </a>

            <a href="#" class="sidebar-link group">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/remove.svg" alt="">
                </div>

                <span class="sidebar-link-label">
                    Reports
                </span>
            </a>

            <a href="#" class="mt-auto sidebar-link group">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img src="/icons/logout.svg" alt="">
                </div>

                <span class="sidebar-link-label">
                    Logout
                </span>
            </a>
        </nav>
    </div>
</template>

<style>
</style>