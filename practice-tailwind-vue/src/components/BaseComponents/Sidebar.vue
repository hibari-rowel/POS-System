<script setup lang="ts">
import { ref, onMounted } from 'vue';
import _ from 'lodash';

const props = defineProps({
    nav_links: {
        type: Object,
        required: true,
        default: () => ({
            main_links: []
        })
    }
});

const isOpen = ref(window.innerWidth >= 768);

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
</script>

<template>
    <div class="sidebar-container" :class="isOpen ? 'w-65' : 'w-0 lg:w-20'">
        <div class="relative flex justify-between text-2xl font-bold mb-6 h-16 items-center"> 
            <div class="flex px-5 gap-4 overflow-hidden transition-opacity duration-300 ease-in-out lg:opacity-100" :class="isOpen ? '' : 'opacity-0'"> 
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
            <router-link v-for="main_link in nav_links.main_links" :to="main_link.route" class="sidebar-link group" active-class="sidebar-link-active">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img :src="main_link.img_src" alt="">
                </div>

                <span class="sidebar-link-label group-hover:text-black">
                    {{ main_link.name }}
                </span>
            </router-link>

            <a href="#" class="mt-auto sidebar-link group" @click="nav_links.bottom_link.action">
                <div class="sidebar-link-img group-hover:shadow-sm">
                    <img :src="nav_links.bottom_link.img_src" alt="">
                </div>

                <span class="sidebar-link-label group-hover:text-black">
                    {{ nav_links.bottom_link.name }}
                </span>
            </a>
        </nav>
    </div>
</template>

<style>
</style>