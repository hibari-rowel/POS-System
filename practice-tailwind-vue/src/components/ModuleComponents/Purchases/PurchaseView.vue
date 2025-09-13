<script setup lang="ts">
import { ref } from 'vue';
import _ from 'lodash';

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const isLoading = ref(false);
const searchQuery = ref('');
const isCategoryOpen = ref(false);
const isOrderOpen = ref(true);
const header = { 
    title: 'Purchases',
    bread_crumbs: [
        {name: "Purchases",},
    ],
};
</script>

<template>
    <Base>
        <template v-slot:topbar-content>
            <div class="flex items-center p-2 rounded-full max-w-55 md:max-w-80 gap-2 bg-gray-100 hover:border-1 hover:border-gray-400 hover:shadow-sm focus-within:border-1 focus-within:border-gray-400 focus-within:shadow-sm transition-all duration-250 ease-in-out">
                <img src="/icons/search.svg" class="p-0 h-full" alt="">
                    
                <input type="text" placeholder="Search Products" class="border-0 outline-none bg-transparent w-25 md:w-75 placeholder-gray-400" v-model="searchQuery">
                    
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
        </template>

        <template v-slot:main-content>
            <Header :header="header"></Header>
        </template>
    </Base>
</template>

<style scoped>
</style>