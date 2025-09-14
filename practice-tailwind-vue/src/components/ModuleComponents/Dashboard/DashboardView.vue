<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const header = { 
    title: 'Dashboard',
    bread_crumbs: [
        // {name: "Books", path: '/books'},
        // {name: "Create Books",},
    ],
};
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <!-- <Header :header="header"></Header> -->

            <div class="h-full py-4 mb-2 bg-white rounded-lg shadow">02</div>
        </template>
    </Base>
</template>

<style scoped></style>