/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.swal = require('sweetalert2');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('tabs', require('./components/Tabs.vue').default);
Vue.component('tab-item', require('./components/TabItem.vue').default);
Vue.component('input-picker', require('./components/InputPicker.vue').default);
Vue.component('judge-score', require('./components/JudgeScore.vue').default);
Vue.component('criteria-score', require('./components/CriteriaScore.vue').default);
Vue.component('alert-judge', require('./components/AlertJudge.vue').default);
Vue.component('alert-admin', require('./components/AlertAdmin.vue').default);
Vue.component('live-score', require('./components/LiveScore.vue').default);
Vue.component('judge-category', require('./components/JudgeCategory.vue').default);

const app = new Vue({
	el: '#app',
});