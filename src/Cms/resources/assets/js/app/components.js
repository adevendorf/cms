import Vue from 'vue';
import SidePanel from './components-reusable/sidepanel.vue';
import Modal from './components-reusable/modal.vue';
import Loader from './components-reusable/loader.vue';
import Paginator from './components-reusable/paginator.vue';
import Confirm from './components-reusable/confirm.vue';
import Ago from './components-reusable/ago.vue';
import DateTimer from './components-reusable/datetimer/datetimer.vue';
import Blocker from './components-reusable/blocker.vue';

Vue.component('side-panel', Vue.extend(SidePanel));
Vue.component('modal', Vue.extend(Modal));
Vue.component('loader', Vue.extend(Loader));
Vue.component('paginator', Vue.extend(Paginator));
Vue.component('confirm', Vue.extend(Confirm));
Vue.component('ago', Vue.extend(Ago));
Vue.component('datetimer', Vue.extend(DateTimer));
Vue.component('blocker', Vue.extend(Blocker));

export default {

}