<script setup lang="ts">
import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';
import router from '../../../router';

const header = { 
    title: 'Product Categories',
    bread_crumbs: [
        {name: "Product Categories"},
    ],
};
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center">
                        <router-link :to="'/product-categories/create'" class="btn-primary"> Create </router-link>
                    </div>
                </template>
            </Header>

            <div class="h-full py-4 mb-2 bg-white rounded-lg shadow">02</div>
        </template>
    </Base>
</template>

<style scoped></style>
