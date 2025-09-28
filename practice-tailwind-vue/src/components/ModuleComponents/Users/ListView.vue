<script setup lang="ts">
import { ref } from 'vue';
import _ from 'lodash';
import { useRouter } from 'vue-router';

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const header = { 
    title: 'Users',
    bread_crumbs: [
        {name: "Users",},
    ],
};

const isLoading = ref(false);

const recordsPerPage = ref(5);

const users = ref([
  { id: 1, name: "Alice Johnson", email: "alice@example.com", role: "admin", status: "active" },
  { id: 2, name: "Bob Smith", email: "bob@example.com", role: "staff", status: "inactive" },
  { id: 3, name: "Charlie Brown", email: "charlie@example.com", role: "customer", status: "active" },
  { id: 4, name: "David Lee", email: "david@example.com", role: "staff", status: "active" },
  { id: 5, name: "Eva Green", email: "eva@example.com", role: "customer", status: "inactive" },
  { id: 6, name: "Frank Wright", email: "frank@example.com", role: "admin", status: "active" },
  { id: 7, name: "Grace Hall", email: "grace@example.com", role: "customer", status: "active" },
  { id: 1, name: "Alice Johnson", email: "alice@example.com", role: "admin", status: "active" },
  { id: 2, name: "Bob Smith", email: "bob@example.com", role: "staff", status: "inactive" },
  { id: 3, name: "Charlie Brown", email: "charlie@example.com", role: "customer", status: "active" },
  { id: 4, name: "David Lee", email: "david@example.com", role: "staff", status: "active" },
  { id: 5, name: "Eva Green", email: "eva@example.com", role: "customer", status: "inactive" },
  { id: 6, name: "Frank Wright", email: "frank@example.com", role: "admin", status: "active" },
  { id: 7, name: "Grace Hall", email: "grace@example.com", role: "customer", status: "active" },
  { id: 1, name: "Alice Johnson", email: "alice@example.com", role: "admin", status: "active" },
  { id: 2, name: "Bob Smith", email: "bob@example.com", role: "staff", status: "inactive" },
  { id: 3, name: "Charlie Brown", email: "charlie@example.com", role: "customer", status: "active" },
  { id: 4, name: "David Lee", email: "david@example.com", role: "staff", status: "active" },
  { id: 5, name: "Eva Green", email: "eva@example.com", role: "customer", status: "inactive" },
  { id: 6, name: "Frank Wright", email: "frank@example.com", role: "admin", status: "active" },
  { id: 7, name: "Grace Hall", email: "grace@example.com", role: "customer", status: "active" },
]);


</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center">
                        <router-link :to="'/users/create'" class="btn-primary"> Create </router-link>
                    </div>
                </template>
            </Header>

            <div class="flex flex-col h-auto mb-2 bg-white rounded-lg shadow">
                <div class="flex flex-col sm:flex-row items-center sm:justify-between h-auto border-b-2 border-gray-50 p-2 mb-1 shadow-sm">
                    <div class="flex items-center w-full mb-0 sm:max-w-100">
                        <div class="flex items-center p-2 rounded-md w-full gap-2 bg-gray-100 border-1 border-gray-300 hover:border-gray-400 hover:shadow-sm focus-within:border-gray-400 focus-within:shadow-sm transition-all duration-250 ease-in-out">
                            <img src="/icons/search.svg" class="p-0 h-full" alt="">
                                
                            <input type="text" placeholder="Search Products" class="border-0 outline-none bg-transparent h-full w-full placeholder-gray-400">
                        </div>
                    </div>

                    <div class="flex items-center justify-center mt-3 sm:mt-0 gap-5">
                        <span>Entries per Page:</span>
                        <select v-model="recordsPerPage" class="border-1 border-gray-300 rounded-md p-1 hover:border-gray-400 hover:shadow-sm focus:border-gray-400 focus:shadow-sm transition-all duration-250 ease-in-out">
                            <option value="5"> 5 </option>
                            <option value="10"> 10 </option>
                            <option value="25"> 25 </option>
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
                            </tr>
                        </thead>

                        <tbody v-if="isLoading">
                            <tr class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-center items-center justify-center w-full" colspan="5">
                                    <span class="inline-flex gap-6 items-center justify-center text-lg">
                                        <div class="loader-dark h-6 w-6"></div> Loading...
                                    </span>
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else-if="_.isEmpty(users)">
                            <tr class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-center items-center justify-center w-full text-base font-bold" colspan="5">
                                    No Users Found.
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else>
                            <tr v-for="user in users" :key="user.id" class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                <td class="flex flex-col sm:flex-row gap-2 px-6 py-3 items-center">
                                    <button class="btn-primary w-8 h-8 p-1">
                                        <img src="/icons/edit.svg" class="p-0 h-[95%] w-[95%]" alt="">
                                    </button>

                                    <button class="btn-danger w-8 h-8 p-1">
                                        <img src="/icons/delete.svg" class="p-0 h-[95%] w-[95%]" alt="">
                                    </button>
                                </td>
                                <td class="px-6 py-3 font-medium text-gray-900">{{ user.name }}</td>
                                <td class="px-6 py-3">{{ user.email }}</td>
                                <td class="px-6 py-3 capitalize">{{ user.role }}</td>
                                <td class="px-6 py-3 capitalize">{{ user.status }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>