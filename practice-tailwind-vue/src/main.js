import { createApp, markRaw } from 'vue'
import { createPinia } from 'pinia'
import router from '@/router/index.js'
import './style.css'
import App from './App.vue'
import inputMask from './lib/inputmask'

import VueSelect from 'vue-select'
import "vue-select/dist/vue-select.css"

const app = createApp(App)
const pinia = createPinia()

pinia.use(({ store }) => {
    store.router = markRaw(router);
})

app.directive('input-mask', inputMask)
app.component("v-select", VueSelect)
app.use(pinia)
app.use(router)
app.mount('#app')
