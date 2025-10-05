<script setup lang="ts">
import { useRoute } from 'vue-router';

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const route = useRoute();
const recordID = route.params.id;
const header = { 
    title: 'Users',
    bread_crumbs: [
        {name: "Users", path: '/users'},
        {name: "Show",},
    ],
};
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <div class="h-fit py-4 mb-2 bg-white rounded-lg shadow">02</div>
            <div class="h-full py-4 mb-2 bg-white rounded-lg shadow">02</div>
        </template>
    </Base>
</template>

<style scoped></style>