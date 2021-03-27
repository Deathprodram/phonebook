require('./bootstrap');
import Vue from 'vue';

Vue.component('navbar', require('./components/Navbar').default);
Vue.component('phonebook', require('./components/Phonebook').default);

window.Vue = Vue;
new Vue({
    el: '#app',
})

