require('./bootstrap');

import Vue from 'vue'
import Vuetify from 'vuetify'
import router from './router'
import store from './store'
import App from './App'

Vue.use(Vuetify)

router.beforeEach((to, from, next) => {
    store.commit('extras/setToolbarTitle', {name:to.name})
    next()
    // if(checkAuth(to.meta.auth, store.state.auth.access, -1)) {
    //     next()
    // } else {
    //     if(checkAuth(store.state.auth.access, [-1, 1, 2, 3, 4])) {
    //         if(checkAuth(store.state.auth.access, [1])) {
    //             next('/dashboard')
    //         }
    //     } else {
    //         next('/')
    //     }
    // }
})

new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App/>'
})