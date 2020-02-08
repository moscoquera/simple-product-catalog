/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import CategoryComponent from "./components/CategoryComponent";

require('./bootstrap');
var VueTruncate = require('vue-truncate-filter')


window.Vue = require('vue');

import VueRouter from 'vue-router';
import CoreuiVue from "@coreui/vue";
import VueAxios from 'vue-axios';
import axios from 'axios'


Vue.use(VueRouter);
Vue.use(CoreuiVue);
Vue.use(VueAxios,axios);
Vue.use(VueTruncate);



import { CSwitch, CButton,CEmitRootEvent, CTooltip } from '@coreui/vue';


import App from "./App";
import TheContainer from "./containers/TheContainer";
import ProductComponent from "./components/ProductComponent";

Vue.component('CButton', CButton);
Vue.directive('c-emit-root-event', CEmitRootEvent);

var url='http://localhost:8001/api'

let routes=[
    {
        name:'home',
        path:'/',
        component:TheContainer,
        children:[
            {
                name:'product',
                path:'product/:id',
                component: ProductComponent,
            },
            {
                name:'category',
                path:':id',
                component: CategoryComponent,
            }

        ]
    },

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
const router = new VueRouter({
    mode:'history',
    routes:routes,
    linkActiveClass: 'nav-item active',
    scrollBehavior: (to) => {
        if (to.hash) {
            return {selector: to.hash}
        } else {
            return { x: 0, y: 0 }
        }
    }
});
const app = new Vue(Vue.util.extend({router},App)).$mount('#app');

export default {
    components: {
        CSwitch,
    },
    directives: {
        'c-tooltip': CTooltip
    }
}
