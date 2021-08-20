/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue').default;

import VueQrcodeReader from "vue-qrcode-reader";
Vue.use(VueQrcodeReader);

import VueMoment from 'vue-moment';
Vue.use(VueMoment);

import {
    ValidationProvider,
    ValidationObserver,
    configure
} from 'vee-validate';
configure({
    classes: {
        valid: "is-valid",
        invalid: "is-invalid",
    },
});
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('qrcode-scan-in-component', require('./components/QrcodeScanIn.vue').default);
Vue.component('qrcode-scan-out-component', require('./components/QrcodeScanOut.vue').default);
Vue.component('stock-in-modal-component', require('./components/StockInModal.vue').default);
Vue.component('stock-out-modal-component', require('./components/StockOutModal.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
