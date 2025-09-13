<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();


</script>

<template>
    <div class="base-container items-center justify-center px-4">
        <div class="flex flex-row h-110 md:h-120 w-full md:w-200 rounded-lg shadow-lg bg-gray-100">
            <div class="login-bg-img hidden md:flex flex-col w-[60%] rounded-l-lg shadow-sm">
                <div class="flex flex-col justify-center w-full h-full rounded-l-lg bg-black/30 p-6">
                    <p class="text-white font-bold text-4xl pl-10">Welcome to <br> ChubbyBuddy</p>
                </div>
            </div>

            <div class="bg-blue-100 w-full md:w-[40%] rounded-lg md:rounded-r-lg md:rounded-l-none border-t-5  border-blue-500 shadow-sm">
                <div class="flex flex-col items-center justify-center h-full p-5">
                    <h2 class="text-3xl font-bold mb-6"> Sign In </h2>

                    <div class="w-full">
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>

                            <div class="flex items-center p-2 rounded-md w-full md:max-w-80 gap-3 bg-gray-100 focus-within:ring-blue-500 focus-within:ring-2 focus-within:border-gray-400 focus-within:shadow-sm transition-all duration-250 ease-in-out">
                                <img src="/icons/email.svg" class="p-0 h-full" alt="">
                                
                                <input type="email" id="email" class="border-0 outline-none bg-transparent w-75 placeholder-gray-400" placeholder="Enter Email">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="block text-gray-700 font-semibold mb-2"> Password </label>

                            <div class="flex items-center p-2 rounded-md w-full md:max-w-80 gap-3 bg-gray-100 focus-within:ring-blue-500 focus-within:ring-2 focus-within:border-gray-400 focus-within:shadow-sm transition-all duration-250 ease-in-out">
                                <img src="/icons/password.svg" class="p-0 h-full" alt="">

                                <input type="password" id="password" class="border-0 outline-none bg-transparent w-75 placeholder-gray-400" placeholder="Enter Password">
                            </div>
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

                        <button type="submit" class="w-full cursor-pointer bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-600 transition-colors duration-300">
                            Login
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>