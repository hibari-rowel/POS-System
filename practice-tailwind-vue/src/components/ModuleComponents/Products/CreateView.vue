<script setup lang="ts">
import Base from '@/components/BaseComponents/Base.vue';
</script>

<template>
    <Base>
        <template v-slot:content>
            <div class="content-topbar">
                <span class="text-2xl font-bold"> Create Products </span>
            </div>

            <div class="flex flex-row h-[89%] rounded-sm shadow-sm bg-gray-100">
                <div class="rounded-r-sm w-full px-5 py-2 bg-gray-100">
                    <span class="text-2xl font-bold"> Create Products Content </span>
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>