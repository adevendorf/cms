import Globals from './globals';
import Vue from 'vue';
// import VueValidator from 'vue-validator';
import VueResource from 'vue-resource';
import router from './router';
import { sync } from 'vuex-router-sync';
import store from './vuex/store';
import Components from './components';
import App from './App.vue';


Globals.init();

// Vue.use(VueValidator);
Vue.use(VueResource);

Vue.http.options.root = "/api/v1";
Vue.http.headers.common['X-CSRF-TOKEN'] = document.getElementById('_token').getAttribute('data-value');
Vue.config.debug = true;

sync(store, router)

router.start(App, '#cms');


