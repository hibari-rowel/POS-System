<script setup lang="ts">
import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const header = { 
    title: 'Product Categories',
    bread_crumbs: [
        {name: "Product Categories", path: '/product-categories'},
        {name: "Create",},
    ],
};
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center gap-1">
                        <router-link :to="'/product-categories'" class="btn-danger"> Cancel </router-link>
                        
                        <button class="btn-primary"> Save </button>
                    </div>
                </template>
            </Header>

            <div class="h-full py-4 mb-2 bg-white rounded-lg shadow">04</div>
        </template>
    </Base>
</template>

<style scoped></style>
