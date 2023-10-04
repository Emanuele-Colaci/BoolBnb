import { createApp } from 'vue'
import App from './App.vue'
import { router } from './router';
import "bootstrap/dist/js/bootstrap.js";
import lottie from 'lottie-web';
import { defineElement } from 'lord-icon-element';
defineElement(lottie.loadAnimation);

createApp(App).use(router).mount('#app')
