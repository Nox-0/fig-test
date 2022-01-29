import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuesax from 'vuesax';
import routes from './routes';

import 'vuesax/dist/vuesax.css' //Vuesax styles
Vue.use(Vuesax);

Vue.use(VueRouter);

let app = new Vue({
    el: '#app',

    router: new VueRouter(routes)
});
