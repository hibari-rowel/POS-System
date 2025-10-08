<script setup lang="ts">
import { reactive, computed, ref } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import { useRouter } from "vue-router";
import { useUserStore } from "@/stores/user.js";
import Swal from 'sweetalert2';
import { fireToast } from "@/lib/toast";

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';
import Modal from '@/components/BaseComponents/Modal.vue';
import TextField from '@/components/FieldComponents/TextField.vue';
import PasswordField from '@/components/FieldComponents/PasswordField.vue';
import DropdownField from '@/components/FieldComponents/DropdownField.vue';
import ImageUploadField from '@/components/FieldComponents/ImageUploadField.vue';

import UserRoleDropdownList from '@/lib/dropdowns/UserRoleDropdownList';
import UserStatusDropdownList from '@/lib/dropdowns/UserStatusDropdownList';

import { createUserValidation } from '@/lib/validations/CreateUserValidation';

const router = useRouter();
const userStore = useUserStore();
const isModalOpen = ref(false);

const header = { 
    title: 'Users',
    bread_crumbs: [
        {name: "Users", path: '/users'},
        {name: "Create",},
    ],
};

const rules = computed(() => createUserValidation(form));

const form = reactive({
    first_name: '',
    middle_name: '',
    last_name: '',
    role: '',
    status: '',
    email: '',
    password: '',
    confirm_password: '',
    image: null,
});

const submitForm = async () => {
    const confirmation = await Swal.fire({
        icon: "question",
        title: "Confirmation",
        text: "Are you sure you want to save this user?",
        showCancelButton: true,
        confirmButtonText: "Save",
        buttonsStyling: false,
        customClass: {
            confirmButton: "btn-primary mx-10",
            cancelButton: "btn-danger mx-10",
        }
    });

    if (confirmation.isConfirmed) {
        Swal.fire({
            title: 'Saving...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()  
            }
        });

        console.log(form);

        let isSuccessfull = await userStore.createUser(form, rules);

        Swal.close();

        if (isSuccessfull) {
            fireToast("success", 'User created successfully.');
            router.push('/users');
        }
    }
};

onBeforeRouteLeave(() => {
    userStore.resetErrors();
});

const toggleModal = (isOpen: boolean) => {
    isModalOpen.value = isOpen;
};
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center gap-1">
                        <router-link :to="'/users'" class="btn-danger"> Cancel </router-link>
                        
                        <button class="btn-primary" @click="submitForm()"> Save </button>
                    </div>
                </template>
            </Header>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 h-full mb-4 rounded-2xl">
                <!-- Profile Section -->
                <div class="bg-white md:col-span-2 xl:col-span-1 rounded-xl shadow-md p-5 flex flex-col items-center justify-start">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 w-full text-center"> Profile Picture </h3>
                    
                    <ImageUploadField :size="'h-72'" :is_required="false" :default_image="'/icons/default_profile.svg'"
                                      :errors="userStore.errors.image" v-model="form.image"/>
                </div>

                <!-- Form Section -->
                <div class="flex flex-col md:col-span-3 xl:col-span-4 gap-6 bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">User Information</h3>

                    <!-- Name Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <TextField id="first_name" label="First Name" placeholder="Enter First Name"
                            :is_required="true" v-model="form.first_name"
                            :errors="userStore.errors.first_name"
                            @clearErrors="userStore.cleanErrors('first_name')" />

                        <TextField id="middle_name" label="Middle Name" placeholder="Enter Middle Name"
                            :is_required="true" v-model="form.middle_name"
                            :errors="userStore.errors.middle_name"
                            @clearErrors="userStore.cleanErrors('middle_name')" />

                        <TextField id="last_name" label="Last Name" placeholder="Enter Last Name"
                            :is_required="true" v-model="form.last_name"
                            :errors="userStore.errors.last_name"
                            @clearErrors="userStore.cleanErrors('last_name')" />
                    </div>

                    <!-- Contact Info -->
                    <div class="grid grid-cols-1 gap-4">
                        <TextField id="email" label="Email" placeholder="Enter Email"
                            :is_required="true" v-model="form.email"
                            :errors="userStore.errors.email"
                            @clearErrors="userStore.cleanErrors('email')" />
                    </div>

                    <!-- Role & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <DropdownField id="role" label="Role" placeholder="Select Role"
                            :is_required="true" :options="UserRoleDropdownList"
                            v-model="form.role"
                            :errors="userStore.errors.role"
                            @clearErrors="userStore.cleanErrors('role')" />

                        <DropdownField id="status" label="Active Status" placeholder="Select Active Status"
                            :is_required="true" :options="UserStatusDropdownList"
                            v-model="form.status"
                            :errors="userStore.errors.status"
                        @clearErrors="userStore.cleanErrors('status')" />
                    </div>

                    <!-- Password Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <PasswordField id="password" label="Password" placeholder="Enter Password"
                            :is_required="true" v-model="form.password"
                            :errors="userStore.errors.password"
                            @clearErrors="userStore.cleanErrors('password')" />

                        <PasswordField id="confirm_password" label="Confirm Password" placeholder="Confirm Password"
                            :is_required="true" v-model="form.confirm_password"
                            :errors="userStore.errors.confirm_password"
                            @clearErrors="userStore.cleanErrors('confirm_password')" />
                    </div>
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped>

</style>