<script setup lang="ts">
import { ref, computed, reactive, watch, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import _ from 'lodash';
import Swal from 'sweetalert2';
import { fireToast } from "@/lib/toast";
import { useUserStore } from "@/stores/user.js";
import DateFormatter from "@/lib/date.ts";

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const router = useRouter();
const userStore = useUserStore();
const header = { 
    title: 'Users',
    bread_crumbs: [
        {name: "Users",},
    ],
};

let searchTimeout : ReturnType<typeof setTimeout>;
const isLoading = ref(false);
const searchQuery = ref("");
const recordsPerPage = ref(5);
const currentPage = ref(1);
const startIndex = ref(0);
const endIndex = computed(() => {
    let end = startIndex.value + recordsPerPage.value;

    return (end > userStore.total_records) ? userStore.total_records : end;
});

const totalPages = computed(() => {
    return Math.ceil(userStore.total_records / recordsPerPage.value);
});

function clickPaginationNavigation(offset: number) {
    startIndex.value += offset; 
    currentPage.value = Math.ceil((startIndex.value + 1) / recordsPerPage.value);
    
    fetchUsers();
}

async function fetchUsers() {
    isLoading.value = true;

    await userStore.getUsers({
        start: startIndex.value,
        records_per_page: recordsPerPage.value,
        search: searchQuery.value,
    });

    isLoading.value = false;
}

const deleteUser = async (userId: string) => {
    const confirmation = await Swal.fire({
        icon: "question",
        title: "Confirmation",
        text: "Are you sure you delete this user?",
        showCancelButton: true,
        confirmButtonText: "Delete",
        buttonsStyling: false,
        customClass: {
            confirmButton: "btn-primary mx-10",
            cancelButton: "btn-danger mx-10",
        }
    });
    
    if (confirmation.isConfirmed) {
        Swal.fire({
            title: 'Deleting...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()  
            }
        });

        let isSuccessfull = await userStore.deleteUser(userId);

        Swal.close();

        if (isSuccessfull) {
            fireToast("success", 'User deleted successfully.');
            await fetchUsers();
        }
    }
};

watch(recordsPerPage, async () => {
    startIndex.value = 0;
    currentPage.value = 1;

    await fetchUsers();
});

watch(searchQuery, async () => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(async () => {
        startIndex.value = 0;
        currentPage.value = 1;

        await fetchUsers();
    }, 300);
});

onMounted(async () => {
    await fetchUsers();
});
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

                            <input type="text" class="border-0 outline-none bg-transparent h-full w-full placeholder-gray-400" placeholder="Search User" v-model="searchQuery" />
                        </div>
                    </div>

                    <div class="flex items-center justify-center mt-3 sm:mt-0 gap-5">
                        <span>Entries per Page:</span>
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

                        <tbody v-if="isLoading">
                            <tr class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-center items-center justify-center w-full" colspan="5">
                                    <span class="inline-flex gap-6 items-center justify-center text-lg">
                                        <div class="loader-dark h-6 w-6"></div> Loading...
                                    </span>
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else-if="_.isEmpty(userStore.users)">
                            <tr class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-center items-center justify-center w-full text-base font-bold" colspan="5">
                                    No Users Found.
                                </td>
                            </tr>
                        </tbody>

                        <tbody v-else>
                            <tr v-for="user in userStore.users" :key="user.id" class="border-b-1 border-gray-200 hover:bg-gray-50 transition">
                                <td class="flex flex-col sm:flex-row gap-2 px-6 py-3 items-center">
                                    <router-link :to="`/users/edit/${user.id}`" class="btn-primary w-8 h-8 p-1">
                                        <img src="/icons/edit.svg" class="p-0 h-[95%] w-[95%]" alt="">
                                    </router-link>

                                    <button class="btn-danger w-8 h-8 p-1" @click="deleteUser(user.id)">
                                        <img src="/icons/delete.svg" class="p-0 h-[95%] w-[95%]" alt="">
                                    </button>
                                </td>

                                <td class="px-6 py-3 font-medium text-gray-900">
                                    <router-link :to="`/users/show/${user.id}`" class="hover:underline">
                                        {{ user.name }}
                                    </router-link>
                                </td>
                                
                                <td class="px-6 py-3">{{ user.email }}</td>
                                <td class="px-6 py-3 capitalize">{{ user.role }}</td>
                                <td class="px-6 py-3 capitalize">{{ user.status }}</td>
                                <td class="px-6 py-3 capitalize">{{ DateFormatter.toReadable(user.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:justify-between h-full w-full p-4">
                    <span class="text-gray-600">Showing {{ startIndex + 1 }} to {{ endIndex }} of {{ userStore.total_records }} entries</span>
                    
                    <div class="flex flex-row items-center gap-2">
                        <button class="px-3 py-1 border rounded disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed" @click="clickPaginationNavigation(-recordsPerPage)" :disabled="currentPage === 1">
                            Prev
                        </button>

                        {{ currentPage }} / {{ _.isEmpty(userStore.users) ? 1 : totalPages }}

                        <button class="px-3 py-1 border rounded disabled:opacity-50 cursor-pointer disabled:cursor-not-allowed" @click="clickPaginationNavigation(recordsPerPage)" :disabled="currentPage === totalPages || _.isEmpty(userStore.users)">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>