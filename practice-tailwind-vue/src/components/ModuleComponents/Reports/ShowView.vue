<script setup lang="ts">
import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const header = { 
    title: 'Reports',
    bread_crumbs: [
        {name: "Reports", path: '/reports'},
        {name: "Show",},
    ],
};
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header"></Header>
        </template>
    </Base>
</template>

<style scoped></style>
