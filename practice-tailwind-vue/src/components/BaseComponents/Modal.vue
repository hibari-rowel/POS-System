<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps({
    show: { type: Boolean, required: true },
    size: { type: String, default: '' },
});

const emit = defineEmits(["close"]);
</script>

<template>
    <Transition name="fade">
        <div v-show="show" class="modal-container-bg" @click="$emit('close')">
            <Transition name="slide">
                <div v-show="show" class="modal-card" :class="size" @click.stop>
                    <div class="modal-header">
                        <slot name="header"></slot>
                        <button @click="$emit('close')" class="text-2xl font-extrabold items-center text-gray-500 hover:text-red-500 transition-all duration-300 ease-in-out cursor-pointer">
                            &times;
                        </button>
                    </div>
                    
                    <div class="modal-body">
                        <slot name="body"></slot>
                    </div>

                    <div class="modal-footer">
                       <slot name="footer"></slot>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</template>

<style scoped>
@reference "tailwindcss";

.fade-enter-active, .fade-leave-active {
   transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
.fade-enter-to, .fade-leave-from {
    opacity: 1;
}

.slide-enter-active, .slide-leave-active {
    transition: transform 0.5s cubic-bezier(.2,.8,.2,1), opacity 0.2s ease;
}
.slide-enter-from {
    transform: translateY(-20px);
    opacity: 0;
}
.slide-enter-to {
    transform: translateY(0);
    opacity: 1;
}
.slide-leave-from {
    transform: translateY(0);
    opacity: 1;
}
.slide-leave-to {
    transform: translateY(-20px);
    opacity: 0;
}

.modal-container-bg {
   @apply fixed inset-0 flex items-center justify-center bg-black/50 z-50
}

.modal-card {
    @apply flex flex-col gap-2 bg-white rounded-lg shadow-lg w-full relative
} 

.modal-header {
    @apply flex flex-row h-auto justify-between items-center border-b-2 border-gray-50 shadow-sm p-2
}

.modal-footer {
    @apply flex flex-row h-auto justify-end items-center border-t-2 border-gray-50 p-2;
    box-shadow: 0 -2px 1px rgba(0, 0, 0, 0.1);
}

.modal-body {
    @apply flex flex-col h-auto p-2
}
</style>