import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import Swal from 'sweetalert2';

export const useAuthStore = defineStore('auth-store', {
    state: () => ({
        user: null,
        errors: {},
        is_loading: false,
    }),

    getters: {
        isAuthenticated: (state) => !_.isEmpty(state.user),
    },

    actions: {
        async login(data) {
            this.is_loading = true;
            this.resetErrors();
            await axios.get('/sanctum/csrf-cookie');

            try {
                const response = await axios.post('/api/login', data);
                this.is_loading = false;
                this.router.push('/dashboard');
            } catch (error) {
                this.is_loading = false;

                let errors = error.response?.data?.errors || {};
                const mergedErrors = _.flatten(_.values(errors)).join(' ');
                this.errors = errors;

                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.querySelector('.swal2-timer-progress-bar').style.backgroundColor = '#ef4444';
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                Toast.fire({
                    icon: "error",
                    title: mergedErrors,
                });
            }
        },
        async logout() {
            try {
                const response = await axios.post('/api/logout');
                this.user = null;
                this.resetErrors();

                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.querySelector('.swal2-timer-progress-bar').style.backgroundColor = '#22c55e';
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                Toast.fire({
                    icon: "success",
                    title: 'Logged out successfully.',
                });

                this.router.push('/');
            } catch (error) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.querySelector('.swal2-timer-progress-bar').style.backgroundColor = '#ef4444';
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                Toast.fire({
                    icon: "error",
                    title: 'Something went wrong. Please contact the administration.',
                });
            }
        },
        async fetchUser() {
            if (_.isEmpty(this.user)) {
                try {
                    const response = await axios.get('/api/fetch_user');
                    this.user = response.data.user;
                } catch (error) {
                    this.user = null;
                }
            }
        },
        resetErrors() {
            this.errors = {};
        }
    }
});