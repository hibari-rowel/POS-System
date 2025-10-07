<script setup lang="ts">
import { ref } from 'vue';

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const isLoading = ref(false);
const searchQuery = ref("");
const recordsPerPage = ref(5);
const currentPage = ref(1);
const startIndex = ref(0);

const header = { 
    title: 'Products',
    bread_crumbs: [
        {name: "Products",},
    ],
};
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center">
                        <router-link :to="'/products/create'" class="btn-primary"> Create </router-link>
                    </div>
                </template>
            </Header>

            <div class="flex flex-col h-auto mb-2 bg-white rounded-lg shadow">
                <div class="flex flex-col sm:flex-row items-center sm:justify-between h-auto border-b-2 border-gray-50 p-2 mb-1 shadow-sm">
                    <div class="flex items-center w-full mb-0 sm:max-w-100">
                        <div class="flex items-center p-2 rounded-md w-full gap-2 bg-gray-100 border-1 border-gray-300 hover:border-gray-400 hover:shadow-sm focus-within:border-gray-400 focus-within:shadow-sm transition-all duration-250 ease-in-out">
                            <img src="/icons/search.svg" class="p-0 h-full" alt="">

                            <input type="text" class="border-0 outline-none bg-transparent h-full w-full placeholder-gray-400" placeholder="Search User" v-model="searchQuery" />
                        </div>
                    </div>

                    <div class="flex items-center justify-center mt-3 sm:mt-0 gap-5">
                        <span> Entries per Page: </span>

                        <select v-model="recordsPerPage" class="border-1 border-gray-300 rounded-md p-1 hover:border-gray-400 hover:shadow-sm focus:border-gray-400 focus:shadow-sm transition-all duration-250 ease-in-out">
                            <option :value="5"> 5 </option>
                            <option :value="10"> 10 </option>
                            <option :value="15"> 15 </option>
                        </select> 
                    </div>
                </div>

                <div class="flex flex-col items-center h-full w-full">
                    <table class="min-w-full text-sm text-left text-gray-600">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-sm border-b-2 border-gray-100">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center w-20"> Actions </th>
                                <th scope="col" class="px-6 py-3"> Name </th>
                                <th scope="col" class="px-6 py-3"> Email </th>
                                <th scope="col" class="px-6 py-3"> Role </th>
                                <th scope="col" class="px-6 py-3"> Status </th>
                                <th scope="col" class="px-6 py-3"> Created At </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>