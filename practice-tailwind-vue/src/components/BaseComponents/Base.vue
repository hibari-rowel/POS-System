<script setup lang="ts">
    import Sidebar from '@/components/BaseComponents/Sidebar.vue';
    import Content from '@/components/BaseComponents/Content.vue';
</script>

<template>
    <div class="base-container">
        <Sidebar />
        <Content>
            <slot name="content"></slot>
        </Content>
        <slot></slot>
    </div>
</template>

<style scoped>
</style>