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
            this.resetErrors();

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();

            if (v$.value.$invalid) {
                for (const key in v$.value.$errors) {
                    let field = v$.value.$errors[key].$property;
                    this.errors[field] = [v$.value.$errors[key].$message];
                }

                return;
            }

            // API here
            console.log('form sent');
        },
        async updateUser(data, rules) {
            this.resetErrors();

            const v$ = useVuelidate(rules, data);
            v$.value.$touch();

            if (v$.value.$invalid) {
                for (const key in v$.value.$errors) {
                    let field = v$.value.$errors[key].$property;
                    this.errors[field] = [v$.value.$errors[key].$message];
                }

                return;
            }


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