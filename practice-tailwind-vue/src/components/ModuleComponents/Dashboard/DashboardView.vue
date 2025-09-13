<script setup lang="ts">
import axios from '@/lib/axios';

import Base from '@/components/BaseComponents/Base.vue';

const getUser = async () => {
    try {
        const response = await axios.get('/api/user');
        console.log('User data:', response.data);
    } catch (error) {
        console.error('Error fetching user data:', error.response.data);
    }
}

// getUser();
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <div class="flex flex-col py-2 mb-4">
                <div class="flex flex-row justify-between">
                    <span class="font-bold text-3xl items-center"> Dashboard </span>
                    <div class="flex justify-end items-center">4</div>
                </div>

                <div class="text-gray-500">breadcrumb here</div>
            </div>

            <div class="py-4 mb-4 bg-white rounded shadow">02</div>
        </template>
    </Base>
</template>

<style scoped></style>