<script setup lang="ts">
import { ref } from 'vue';

import Base from '@/components/BaseComponents/Base.vue';
import Header from '@/components/BaseComponents/Header.vue';
import TextField from '@/components/FieldComponents/TextField.vue';
import PasswordField from '@/components/FieldComponents/PasswordField.vue';
import DropdownField from '@/components/FieldComponents/DropdownField.vue';

import UserRoleDropdownList from '@/lib/dropdowns/UserRoleDropdownList';
import UserStatusDropdownList from '@/lib/dropdowns/UserStatusDropdownList';

const header = { 
    title: 'Users',
    bread_crumbs: [
        {name: "Users", path: '/users'},
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
                        <router-link :to="'/users'" class="btn-danger"> Cancel </router-link>
                        
                        <button class="btn-primary"> Save </button>
                    </div>
                </template>
            </Header>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 h-full mb-2 bg-gray-100 rounded-lg">
                <div class="bg-white md:col-span-1 rounded-lg shadow p-5">

                </div>

                <div class="flex flex-col gap-1 md:gap-3 bg-white md:col-span-3 rounded-lg shadow p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-1 md:gap-5">
                        <TextField :id="'first_name'" :label="'First Name'" :placeholder="'Enter First Name'" :is_required="true" :is_disabled="false" :errors="[]"/>
                        <TextField :id="'middle_name'" :label="'Middle Name'" :placeholder="'Enter Middle Name'" :is_required="true" :is_disabled="false" :errors="[]"/>
                        <TextField :id="'last_name'" :label="'Last Name'" :placeholder="'Enter Last Name'" :is_required="true" :is_disabled="false" :errors="[]"/>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-1 md:gap-5">
                        <DropdownField :id="'role'" :label="'Role'" :is_required="true" :options="UserRoleDropdownList" :placeholder="'Select Role'"/>
                        <DropdownField :id="'status'" :label="'Active Status'" :is_required="true" :options="UserStatusDropdownList" :placeholder="'Select Active Status'"/>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-1 md:gap-5">
                        <TextField :id="'email'" :label="'Email'" :placeholder="'Enter Email'" :is_required="true" :is_disabled="false" :errors="[]"/>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-1 md:gap-5">
                        <PasswordField :id="'password'" :label="'Password'" :placeholder="'Enter Password'" :is_required="true" :is_disabled="false" :errors="[]"/>
                        <PasswordField :id="'confirm_password'" :label="'Confirm Password'" :placeholder="'Confirm Password'" :is_required="true" :is_disabled="false" :errors="[]"/>
                    </div>                
                </div>
            </div>
        </template>
    </Base>
</template>

<style scoped></style>