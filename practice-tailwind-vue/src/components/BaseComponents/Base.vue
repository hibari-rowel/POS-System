<script setup lang="ts">
import Sidebar from '@/components/BaseComponents/Sidebar.vue';
import Content from '@/components/BaseComponents/Content.vue';
import Orders from '@/components/BaseComponents/Orders.vue';
</script>

<template>
    <div class="flex h-screen bg-gray-200 overflow-hidden">
        <Sidebar />
        <Content />
        <Orders />
    </div>
</template>

<style scoped>

</style>