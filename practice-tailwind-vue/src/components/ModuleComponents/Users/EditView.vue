<script setup lang="ts">
import { reactive, computed, ref, onMounted } from 'vue';
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router';
import { useUserStore } from "@/stores/user.js";
import Swal from 'sweetalert2';
import { fireToast } from "@/lib/toast";

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';
import TextField from '@/components/FieldComponents/TextField.vue';
import DropdownField from '@/components/FieldComponents/DropdownField.vue';
import ModalField from '@/components/FieldComponents/ModalField.vue';
import ImageUploadField from '@/components/FieldComponents/ImageUploadField.vue';

import UserRoleDropdownList from '@/lib/dropdowns/UserRoleDropdownList';
import UserStatusDropdownList from '@/lib/dropdowns/UserStatusDropdownList';

import { createUserValidation } from '@/lib/validations/CreateUserValidation';

const router = useRouter();
const userStore = useUserStore();
const route = useRoute();
const recordID = route.params.id;
const header = { 
    title: 'Users',
    bread_crumbs: [
        {name: "Users", path: '/users'},
        {name: "Edit",},
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
    image: null,
});

const submitForm = async () => {
    const confirmation = await Swal.fire({
        icon: "question",
        title: "Confirmation",
        text: "Are you sure you want to edit this user?",
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

        let isSuccessfull = await userStore.updateUser(form, rules, recordID);

        Swal.close();

        if (isSuccessfull) {
            fireToast("success", 'User updated successfully.');
            router.push('/users');
        }
    }
};

onMounted(async () => {
    const user = await userStore.getUser(recordID);
    if (user) {
        form.first_name = user.first_name;
        form.middle_name = user.middle_name;
        form.last_name = user.last_name;
        form.role = user.role;
        form.status = user.status;
        form.email = user.email;
        form.image = user.image;
    }
});

onBeforeRouteLeave(() => {
    userStore.resetErrors();
});
</script>

<template>
    <Base>
        <template v-slot:main-content>
            <Header :header="header">
                <template v-slot:right-side>
                    <div class="flex items-center gap-1">
                        <router-link :to="'/users/show/' + recordID" class="btn-danger"> Cancel </router-link>
                        <button class="btn-primary" @click="submitForm()"> Save </button>
                    </div>
                </template>
            </Header>

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 h-full mb-2 bg-gray-100 rounded-lg">
                <div class="bg-white md:col-span-1 rounded-lg shadow p-5 h-fit">
                    <ImageUploadField :label="'Profile Picture'" :size="'h-75'" :is_required="false" :errors="userStore.errors.image" v-model="form.image"/>
                </div>

                <div class="flex flex-col gap-1 md:gap-3 bg-white md:col-span-4 rounded-lg shadow p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-5">
                        <TextField :id="'first_name'" :label="'First Name'" :placeholder="'Enter First Name'" :is_required="true" 
                                   :is_disabled="false" v-model="form.first_name" :errors="userStore.errors.first_name"
                                   @clearErrors="userStore.cleanErrors('first_name')"/>

                        <TextField :id="'middle_name'" :label="'Middle Name'" :placeholder="'Enter Middle Name'" :is_required="true" 
                                   :is_disabled="false" v-model="form.middle_name" :errors="userStore.errors.middle_name"
                                   @clearErrors="userStore.cleanErrors('middle_name')"/>

                        <TextField :id="'last_name'" :label="'Last Name'" :placeholder="'Enter Last Name'" :is_required="true" 
                                   :is_disabled="false" v-model="form.last_name" :errors="userStore.errors.last_name"
                                   @clearErrors="userStore.cleanErrors('last_name')"/>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-1 md:gap-5">
                        <TextField :id="'email'" :label="'Email'" :placeholder="'Enter Email'" :is_required="true" 
                                   :is_disabled="false" v-model="form.email" :errors="userStore.errors.email"
                                   @clearErrors="userStore.cleanErrors('email')"/>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-1 md:gap-5">
                        <DropdownField :id="'role'" :label="'Role'" :placeholder="'Select Role'" :is_required="true" :is_disabled="false" 
                                       :options="UserRoleDropdownList" v-model="form.role" :errors="userStore.errors.role" 
                                       @clearErrors="userStore.cleanErrors('role')"/>

                        <DropdownField :id="'status'" :label="'Active Status'" :placeholder="'Select Active Status'" :is_required="true" :is_disabled="false" 
                                       :options="UserStatusDropdownList" v-model="form.status" :errors="userStore.errors.status"
                                       @clearErrors="userStore.cleanErrors('status')"/>
                    </div>                
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>