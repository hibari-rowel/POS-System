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
        <template v-slot:topbar-content>
            <span class="text-2xl font-bold"> Dashboard </span>
        </template>

        <template v-slot:main-content>
            
        </template>
    </Base>
</template>

<style scoped></style>