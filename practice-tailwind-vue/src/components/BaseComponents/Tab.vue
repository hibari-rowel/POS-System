<script setup lang="ts">
import { inject, computed } from "vue"

const activeTab = inject("activeTab");
const setActiveTab = inject("setActiveTab");

const props = defineProps({
    name: {type: String, required: true},
    label: {type: String, required: true},
});


const isActive = computed(() => activeTab.value === props.name)
</script>

<template>
    <template v-if="$slots.default">
        <div v-show="isActive">
            <slot></slot>
        </div>
    </template>

    <template v-else>
        <button class="tab-navigation" @click="setActiveTab(props.name)" :class="isActive ? 'active-tab-navigation' : 'inactive-tab-navigation'">
            {{ label }}
        </button>
    </template>
</template>

<style scoped>
@reference "tailwindcss";

.active-tab-navigation {
    @apply border-blue-500 bg-blue-400 rounded-tr-lg rounded-tl-lg font-bold text-white hover:text-black hover:bg-blue-200 transition-all duration-300 ease-in-out;
    box-shadow: 0 -1px 1px rgba(0, 0, 0, 0.1);
}

.inactive-tab-navigation {
    @apply text-gray-500 border-transparent hover:text-black transition-all duration-300 ease-in-out
}

.tab-navigation {
    @apply px-4 py-2 border-t-2 border-l-2 border-r-2 text-base font-medium focus:outline-none cursor-pointer
}
</style>