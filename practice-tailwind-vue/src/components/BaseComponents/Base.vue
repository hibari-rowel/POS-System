<script setup lang="ts">
import Sidebar from '@/components/BaseComponents/Sidebar.vue';
import Content from '@/components/BaseComponents/Content.vue';
import Orders from '@/components/BaseComponents/Orders.vue';
</script>

<template>
    <div class="flex flex-col h-screen overflow-hidden bg-gray-200 md:flex-row">
        <Sidebar />
        <Content />
        <Orders />
    </div>
</template>

<style scoped>

</style>