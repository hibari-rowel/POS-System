<script setup lang="ts">
import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';

const header = { 
    title: 'Reports',
    bread_crumbs: [
        {name: "Reports",},
    ],
};
</script>

<template>
   <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center">
                        <router-link :to="'/reports/create'" class="btn-primary"> Create </router-link>
                    </div>
                </template>
            </Header>

            <div class="h-full py-4 mb-2 bg-white rounded-lg shadow">02</div>
        </template>
    </Base>
</template>

<style scoped></style>
