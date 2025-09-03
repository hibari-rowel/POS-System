<script setup lang="ts">
import { ref, reactive } from 'vue';
import _ from 'lodash';

const isLoading = ref(false);
const searchQuery = ref('');
const isOpen = ref(false);

</script>

<template>
    <div class="flex-1 flex flex-col w-full m-2">
        <div class="flex items-center justify-between h-17 px-5 py-4 mb-4 shadow-sm rounded-sm bg-white">
            <div class="flex items-center p-2 rounded-full max-w-80 gap-3 bg-gray-100 hover:border-1 hover:border-gray-400 hover:shadow-sm focus-within:border-1 focus-within:border-gray-400 focus-within:shadow-sm transition-all duration-250 ease-in-out">
                <img src="/icons/search.svg" class="p-0 h-full" alt="">
                
                <input type="text" placeholder="Search Products" class="border-0 outline-none bg-transparent w-75 placeholder-gray-400" v-model="searchQuery">
                
                <div class="flex items-center justify-center h-full mr-2">
                    <img v-if="isLoading" src="/icons/loading.svg" class="p-0 h-full animate-spin shrink-0 rounded-full" alt="">
                
                    <button v-else-if="!isLoading && !_.isEmpty(searchQuery)" @click="searchQuery = ''" class="flex items-center justify-center h-full cursor-pointer shrink-0 rounded-full active:bg-red-200">
                        <img src="/icons/close.svg" class="p-1 h-full" alt=""></img>
                    </button>

                    <button v-else-if="!isLoading && _.isEmpty(searchQuery)" @click="console.log('Open Scanner')" class="flex items-center justify-center h-full cursor-pointer shrink-0 rounded-full active:bg-blue-200">
                        <img src="/icons/barcode_scanner.svg" class="p-1 h-full" alt=""></img>
                    </button>
                </div>
            </div>
            
            <div class="flex gap-4 items-center h-full">
                <button class="flex items-center justify-center shrink-0 h-full rounded-full cursor-pointer border-3 border-transparent transition-all duration-250 ease-in-out hover:shadow-sm hover:border-blue-500">
                    <img src="/icons/settings.svg" class="p-0 m-0 h-full active:bg-blue-200 bg-white rounded-full group-hover:shadow-md" alt=""></img>
                </button>
            </div>
        </div>
<!-- 
        <div class="flex px-5 py-3 h-50 mb-4 rounded-sm shadow-sm bg-white">
            category FOR SMALL SCREENS
        </div> -->

        <div class="flex flex-row h-screen rounded-sm shadow-sm bg-gray-100">
            <!-- hide me when screen is small -->
            <div class="flex flex-col py-3 rounded-sm shadow-sm transition-all duration-900 ease-in-out bg-white" :class="isOpen ? 'w-50 px-5' : 'w-0 px-0'">
                <div class="relative flex justify-between mb-3 items-center"> 
                    <span class="text-lg font-bold text-wrap overflow-hidden">Categories:</span>

                    <button @click="isOpen = !isOpen" class="absolute flex p-0 h-10 w-10 items-center justify-center border-r-4 border-transparent shrink-0 rounded-r-sm cursor-pointer hover:border-blue-500 transition-right duration-900 ease-in-out bg-white" :class="isOpen ? '-right-15' : '-right-10 shadow-sm'">
                        <img src="/icons/category.svg" class="p-1 h-full w-full transition-transform duration-1000 ease-in-out" :class="isOpen ? '-rotate-180' : 'rotate-none'" alt="">
                    </button>
                </div>

                <span>asdf</span>
            </div>

            <div class="rounded-r-sm w-full px-5 py-2 bg-gray-100">
                <span class="text-2xl font-bold transition-margin duration-900 ease-in-out" :class="isOpen ? 'ml-8' : 'ml-7'"> Products </span>
            </div>
        </div>
    </div>
</template>

<style>
</style>