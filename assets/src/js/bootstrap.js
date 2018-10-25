
window.Vue = require('vue');
window._ = require('lodash');
window.axios = require('axios');
window.qs = require('qs');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);