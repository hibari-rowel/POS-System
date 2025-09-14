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
        <template v-slot:topbar-content>
            <span class="text-2xl font-bold"> Dashboard </span>
        </template>

        <template v-slot:main-content>
            <div class="grid grid-cols-3 gap-4 h-full p-4 mb-2 bg-white rounded-lg shadow">
                <div class="bg-red-200 p-4">1</div>
                <div class="bg-green-200 p-4">2</div>
                <div class="bg-blue-200 p-4">3</div>
                <div class="bg-yellow-200 p-4">4</div>
                <div class="bg-orange-200 p-4">5</div>
                <div class="bg-violet-200 p-4">6</div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>