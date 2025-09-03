<script setup lang="ts">
import { ref } from 'vue';

const isOpen = ref(true);
</script>

<template>
    <div class="flex flex-col shadow-sm py-3 mr-0 rounded-sm transition-all duration-900 ease-in-out origin-left min-w-0 bg-gray-50" :class="isOpen ? 'w-90 ml-5' : 'w-0 ml-10'">
        <div class="relative flex justify-between text-2xl font-bold mb-6 h-16 items-center"> 
            <div class="flex px-5 gap-4 overflow-hidden transition-all duration-1000 ease-in-out" :class="isOpen ? 'opacity-100 w-full' : 'opacity-0'"> 
                <div class="h-10 w-10 flex shrink-0 items-center justify-center rounded-full group-hover:shadow-sm bg-gray-200 p-1">
                    <img src="/icons/remove.svg" alt="">
                </div>

                <span class="text-nowrap"> Current Orders </span>
            </div>

            <button class="hover-left-border absolute cursor-pointer border-l-4 border-transparent p-0 h-10 w-10 flex items-center justify-center shrink-0 rounded-l-sm bg-gray-50 hover:border-blue-500
                    transition-left duration-900 ease-in-out" 
                    :class="isOpen ? '-left-6' : '-left-10'" @click="isOpen = !isOpen">
                <img src="/icons/shopping_cart.svg" class="p-0 h-7 w-7" alt="">
            </button>
        </div>

        <div class="flex flex-col h-full gap-1"></div>
    </div>
</template>

<style scoped>
.hover-left-border {
    transition: left 930ms ease-in-out, border-color 250ms ease-in-out;
}
</style>