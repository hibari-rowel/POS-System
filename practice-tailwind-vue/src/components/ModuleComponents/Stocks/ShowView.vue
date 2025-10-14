<script setup lang="ts">
import { useRoute } from 'vue-router';

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const route = useRoute();
const recordID = route.params.id;
const header = { 
    title: 'Products',
    bread_crumbs: [
        {name: "Products", path: '/products'},
        {name: "Show",},
    ],
};
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center gap-1">
                        <router-link :to="'/products'" class="btn-danger"> Cancel </router-link>
                        <router-link :to="'/products/edit/' + recordID" class="btn-primary"> Edit </router-link>
                    </div>
                </template>
            </Header>

            <div class="h-full py-4 mb-2 bg-white rounded-lg shadow">recordID {{ recordID }}</div>
        </template>
    </Base>
</template>

<style scoped></style>