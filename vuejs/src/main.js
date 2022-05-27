import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import router from './router'
import store from './store';
import axios from 'axios';

axios.defaults.withCredentials = false
axios.defaults.baseURL = 'http://142.93.234.155/api/';

Vue.config.productionTip = false

new Vue({
  store,
  vuetify,
  router,
  render: h => h(App)
}).$mount('#app')
