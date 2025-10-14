<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useUserStore } from "@/stores/user.js";
import Swal from 'sweetalert2';

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';
import Tabs from '@/components/BaseComponents/Tabs.vue';
import Tab from '@/components/BaseComponents/Tab.vue';

const userStore = useUserStore();
const route = useRoute();
const recordID = route.params.id;
const user = reactive({
    name: '',
    role: '',
    email: '',
    status: '',
    image: '',
});

onMounted(async () => {
    Swal.fire({
        title: 'Loading Record...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    const userData = await userStore.getUser(recordID);
    if (userData) {
        user.name = userData.full_name;
        user.role = userData.role;
        user.email = userData.email;
        user.status = userData.status;
        user.image = userData.image;
    }

    Swal.close();
});
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <div class="flex flex-col justify-between h-fit w-full p-4 mb-2 bg-white rounded-2xl shadow gap-6">
                <div class="flex flex-row justify-between">
                    <div class="flex flex-row items-center gap-3">
                        <div class="w-2 h-10 rounded-full bg-gradient-to-b from-blue-500 to-blue-700"></div>
                        <h1 class="text-3xl font-bold tracking-tight text-gray-800">User Profile</h1>
                    </div>

                    <div class="flex items-center gap-1">
                        <router-link :to="'/users'" class="btn-danger"> Back </router-link>
                        <router-link :to="'/users/edit/' + recordID" class="btn-primary"> Edit </router-link>
                    </div>
                </div>
                
                <div class="flex flex-col gap-6">
                    <div class="flex flex-row lg:flex-row gap-6 lg:gap-5 items-center lg:items-start">
                        <div class="flex flex-col items-center justify-between w-40 h-40">
                            <img :src="user.image" alt="" class="w-40 h-40 rounded-xl object-cover shadow-md ring-2 ring-gray-400 hover:scale-101 transition-transform duration-300"/>
                        </div>

                        <div class="flex flex-col gap-4">
                            <h1 class="text-2xl font-bold"> {{ user.name }} </h1>

                            <div class="grid grid-cols-2 lg:grid-cols-3 h-fit gap-4 md:gap-5 lg:gap-10 xl:gap-1 mb-3">
                                <div class="flex flex-col gap-1">
                                    <h2 class="text-sm font-medium text-gray-500"> Role </h2>
                                    <span class="text-lg font-semibold text-gray-800 capitalize"> {{ user.role }} </span>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <h2 class="text-sm font-medium text-gray-500"> Status </h2>
                                    <span class="text-lg font-semibold text-gray-800 capitalize"> {{ user.status }} </span>
                                </div>

                                <div class="flex flex-col gap-1">
                                    <h2 class="text-sm font-medium text-gray-500"> Email Address </h2>
                                    <span class="text-lg font-semibold text-gray-800"> {{ user.email }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                        <div class="flex flex-col w-full items-start bg-gradient-to-br from-blue-200 to-white shadow-sm rounded-lg p-4 hover:shadow-md transition ring-2 ring-gray-200">
                            <h3 class="text-sm font-medium text-gray-500">Attendance</h3>
                            <h2 class="text-2xl font-bold text-blue-600">1</h2>
                        </div>

                        <div class="flex flex-col w-full items-start bg-gradient-to-br from-blue-200 to-white shadow-sm rounded-lg p-4 hover:shadow-md transition ring-2 ring-gray-200">
                            <h3 class="text-sm font-medium text-gray-500">Total Sales</h3>
                            <h2 class="text-2xl font-bold text-blue-600">1</h2>
                        </div>

                        <div class="flex flex-col w-full items-start bg-gradient-to-br from-blue-200 to-white shadow-sm rounded-lg p-4 hover:shadow-md transition ring-2 ring-gray-200">
                            <h3 class="text-sm font-medium text-gray-500">Avg. Time-In</h3>
                            <h2 class="text-2xl font-bold text-blue-600">1</h2>
                        </div>

                        <div class="flex flex-col w-full items-start bg-gradient-to-br from-blue-200 to-white shadow-sm rounded-lg p-4 hover:shadow-md transition ring-2 ring-gray-200">
                            <h3 class="text-sm font-medium text-gray-500">Avg. Time-Out</h3>
                            <h2 class="text-2xl font-bold text-blue-600">1</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col h-auto py-4 px-2 mb-2 bg-white rounded-lg shadow">
                <Tabs defaultTab="attendance">
                    <template #tabs>
                        <Tab name="attendance" label="Attendance" />
                        <Tab name="sales" label="Sales" />
                    </template>

                    <Tab name="attendance" label="Attendance">
                        <h2 class="text-lg font-semibold">Attendance</h2>
                        <p>Your profile Attendance go here.</p>
                    </Tab>
                    
                    <Tab name="sales" label="Sales">
                        <h2 class="text-lg font-semibold">Sales</h2>
                        <p>Manage your Sales here.</p>
                    </Tab>
                </Tabs>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>