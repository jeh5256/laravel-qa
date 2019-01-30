
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueIzioast from 'vue-izitoast';
import { type } from 'os';
import authorize from './authorizations/authorize';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 //Middleware
Vue.use(VueIzioast);
Vue.use(authorize);

//Comonents
Vue.component('author-info', require('./components/AuthorInfo.vue').default);
Vue.component('answer', require('./components/Answer.vue').default);
Vue.component('answers', require('./components/Answers.vue').default);
Vue.component('vote', require('./components/Vote.vue').default);

const app = new Vue({
    el: '#app'
});
