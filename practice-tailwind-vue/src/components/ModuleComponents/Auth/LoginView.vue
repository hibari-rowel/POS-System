<script setup lang="ts">
import { reactive, computed } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import { useAuthStore } from "@/stores/auth.js";
import { helpers, required, email } from "@vuelidate/validators";
import _ from 'lodash';

import TextField from '@/components/FieldComponents/TextField.vue';
import PasswordField from '@/components/FieldComponents/PasswordField.vue';

const authStore = useAuthStore();

const form = reactive({
    email: '',
    password: '',
});

const rules = computed(() => ({
    email: {
        required: helpers.withMessage("Email is required", required),
        email: helpers.withMessage("Please enter a valid Email", email)
    },
    password: { 
        required:  helpers.withMessage("Password is required", required),
    },
}));

const submitForm = () => {
    authStore.login(form, rules);
};

onBeforeRouteLeave(() => {
    authStore.resetErrors();
});
</script>

<template>
    <div class="base-container items-center justify-center px-4">
        <div class="flex flex-row h-110 md:h-120 w-full md:w-200 rounded-lg shadow-lg bg-gray-100">
            <div class="login-bg-img hidden md:flex flex-col w-[60%] rounded-l-lg shadow-sm">
                <div class="flex flex-col justify-center w-full h-full rounded-l-lg bg-black/30 p-6">
                    <p class="text-white font-bold text-4xl pl-10">Welcome to <br> ChubbyBuddy</p>
                </div>
            </div>

            <div class="bg-blue-100 w-full md:w-[40%] rounded-lg md:rounded-r-lg md:rounded-l-none border-t-5 border-blue-500 shadow-sm">
                <div class="flex flex-col items-center justify-center h-full p-5">
                    <h2 class="text-3xl font-bold mb-6"> Sign In </h2>

                    <div class="w-full">
                        <div class="mb-4">
                            <TextField :id="'email'" :label="'Email'" :placeholder="'Enter Email'" :is_required="true" :is_disabled="false" 
                                :errors="authStore.errors.email" @clearErrors="authStore.cleanErrors('email')" v-model="form.email"/>
                        </div>

                        <div class="mb-4">
                            <PasswordField :id="'password'" :label="'Password'" :placeholder="'Enter Password'" :is_required="true" :is_disabled="false" 
                                :errors="authStore.errors.password" @clearErrors="authStore.cleanErrors('password')" v-model="form.password"/>
                        </div>

                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember" class="mr-2">

                                <label for="remember" class="text-gray-700 text-sm"> Remember Me </label>
                            </div>

                            <a href="#" class="text-blue-500 text-sm">
                                Forgot Password?
                            </a>
                        </div>

                        <button class="flex items-center justify-center w-full cursor-pointer bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 transition-colors duration-300" 
                                @click="submitForm()" :disabled="authStore.is_loading">
                            <span v-if="authStore.is_loading" class="inline-flex gap-2 items-center justify-center"> 
                                <div class="loader"></div> Signing In...
                            </span>

                            <span v-else class="inline-flex gap-2 items-center justify-center"> Sign In </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>