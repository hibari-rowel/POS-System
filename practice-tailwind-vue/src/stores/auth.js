import { defineStore } from "pinia";
import axios from '@/lib/axios';
import _ from 'lodash';
import Swal from 'sweetalert2'

export const useAuthStore = defineStore('auth-store', {
    state: () => ({
        user: null,
        error: {},
    }),

    getters: {
        isAuthenticated: (state) => !_.isEmpty(state.user),
    },

    actions: {
        async login(data) {
            await axios.get('/sanctum/csrf-cookie');
            try {
                const response = await axios.post('/api/login', data);
            } catch (error) {
                console.log(error);
            }
        },
        logout() {
            try {

            } catch (error) {

            }
        },
        fetchUser() {
            try {

            } catch (error) {

            }
        }
    }
});