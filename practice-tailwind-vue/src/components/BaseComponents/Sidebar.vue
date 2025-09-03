<script setup lang="ts">
    import { ref } from 'vue';

    const isOpen = ref(true);
</script>

<template>
    <div class="flex flex-col shadow-sm px-0 py-3 mr-5 rounded-sm transition-all duration-700 ease-in-out bg-gray-50" :class="isOpen ? 'w-70' : 'w-19'">
        <div class="relative flex justify-between text-2xl font-bold mb-6 h-16 items-center"> 
            <div class="flex px-5 gap-4 overflow-hidden"> 
                <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-full group-hover:shadow-sm bg-gray-200 p-1">
                    <img src="/icons/remove.svg" alt="">
                </div>

                <span class="text-nowrap"> My App </span>
            </div>

            <button @click="isOpen = !isOpen" class="absolute cursor-pointer border-r-4 border-transparent transition-all duration-250 ease-in-out -right-6 p-0 h-10 w-10 flex items-center justify-center shrink-0 rounded-r-sm hover:border-blue-500 bg-gray-50">
                <img src="/icons/arrow_right.svg" class="p-0 h-full w-full transition-transform duration-1000 ease-in-out" :class="isOpen ? '-rotate-180' : 'rotate-none'" alt="">
            </button>
        </div>

        <nav class="flex flex-col h-full gap-1">
            <a href="#" class="group flex gap-4 h-14 px-5 rounded-tr-sm rounded-br-sm items-center border-r-4 border-transparent transition-all duration-250 ease-in-out hover:bg-blue-300 hover:shadow-sm hover:border-blue-500">
                <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-full group-hover:shadow-sm bg-gray-200 p-1">
                    <img src="/icons/remove.svg" alt="">
                </div>

                <span class="text-nowrap text-lg font-semibold overflow-x-hidden">
                    Analytics
                </span>
            </a>
            
            <a href="#" class="group flex gap-4 h-14 px-5 rounded-tr-sm rounded-br-sm items-center border-r-4 border-transparent transition-all duration-250 ease-in-out hover:bg-blue-300 hover:shadow-sm hover:border-blue-500">
                <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-full group-hover:shadow-sm bg-gray-200 p-1">
                    <img src="/icons/remove.svg" alt="">
                </div>

                <span class="text-nowrap text-lg font-semibold overflow-x-hidden">
                    Purchase Product
                </span>
            </a>

            <a href="#" class="group flex gap-4 h-14 px-5 rounded-tr-sm rounded-br-sm items-center border-r-4 border-transparent transition-all duration-250 ease-in-out hover:bg-blue-300 hover:shadow-sm hover:border-blue-500">
                <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-full group-hover:shadow-sm bg-gray-200 p-1">
                    <img src="/icons/remove.svg" alt="">
                </div>

                <span class="text-nowrap text-lg font-semibold overflow-x-hidden">
                    Purchase History
                </span>
            </a>

            <a href="#" class="group flex gap-4 h-14 px-5 rounded-tr-sm rounded-br-sm items-center border-r-4 border-transparent transition-all duration-250 ease-in-out hover:bg-blue-300 hover:shadow-sm hover:border-blue-500">
                <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-full group-hover:shadow-sm bg-gray-200 p-1">
                    <img src="/icons/remove.svg" alt="">
                </div>

                <span class="text-nowrap text-lg font-semibold overflow-x-hidden">
                    Reports
                </span>
            </a>

            <a href="#" class="group flex gap-4 h-14 px-5 rounded-tr-sm rounded-br-sm mt-auto items-center border-r-4 border-transparent transition-all duration-250 ease-in-out hover:bg-blue-300 hover:shadow-sm hover:border-blue-500">
                <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-full group-hover:shadow-sm bg-gray-200 p-1">
                    <img src="/icons/logout.svg" alt=""></img>
                </div>

                <span class="text-nowrap text-lg font-semibold overflow-x-hidden">
                    Logout
                </span>
            </a>
        </nav>
    </div>
</template>

<style>
</style>