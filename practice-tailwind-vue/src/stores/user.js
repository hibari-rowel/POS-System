import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import Swal from 'sweetalert2';
import useVuelidate from "@vuelidate/core"

export const useUserStore = defineStore('user-store', {
    state: () => ({
        user: null,
        users: {},
        errors: {},
    }),

    getters: {

    },

    actions: {
        async createUser(data, rules) {

        },
        async updateUser(data, rules) {

        },
        async getUser(data) {

        },
        async getUsers(data) {

        },
        resetErrors() {
            this.errors = {};
        },
        cleanErrors(field) {
            this.errors[field] = null
        }
    },
});