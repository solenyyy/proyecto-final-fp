import { createApp } from 'vue'
import App from './App.vue'
import { router } from './router'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import './style.css'
import '@fortawesome/fontawesome-free/css/all.min.css'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import 'reflect-metadata'

createApp(App)
    .use(router)
    .use(Toast, {
        position: 'top-center',
        timeout: 3000,
        closeOnClick: true,
        pauseOnHover: true,
    })
    .mount('#app')