require('./bootstrap');

import Vue from 'vue'
import Vuetify from 'vuetify'
import router from './router'
import store from './store'
import App from './App'

Vue.use(Vuetify)

new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App/>'
})