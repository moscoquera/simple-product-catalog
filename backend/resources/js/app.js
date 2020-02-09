/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


import categories_new  from "./components/categories/NewComponent";
import categories_index  from "./components/categories/IndexComponent";
import categories_edit  from "./components/categories/EditComponent";
import products_new  from "./components/products/NewComponent";
import products_index  from "./components/products/IndexComponent";
import products_edit  from "./components/products/EditComponent";

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueAxios from 'vue-axios';
import axios from 'axios';
import Paginate from 'vuejs-paginate';
import App from "./App";


Vue.use(VueAxios,axios);
Vue.component('paginate', Paginate)

import HomeComponent from "./components/HomeComponent";

let routes=[
    {
        name:'home',
        path:'/',
        component:HomeComponent
    },
    {
        name:'categories_new',
        path:'/categories/new',
        component: categories_new
    },
    {
        name:'categories_index',
        path:'/categories',
        component:categories_index
    },
    {
        name:'categories_edit',
        path:'/categories/edit/:id',
        component:categories_edit
    },
    {
        name:'products_new',
        path:'/products/new',
        component:products_new
    },
    {
        name:'products_index',
        path:'/products',
        component:products_index
    },
    {
        name:'products_edit',
        path:'/products/edit/:id',
        component:products_edit
    }
];

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => {
    Vue.component(key.split('/').pop().split('.')[0], files(key).default)

})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const router = new VueRouter({mode:'history',routes:routes});
const app = new Vue(Vue.util.extend({router},App)).$mount('#app');
