require('./bootstrap');

import Vue from 'vue'
import Vuetify from 'vuetify'
import VueCookies from 'vue-cookies'
import router from './router'
import store from './store'
import App from './App'
import checkAuth from './utils/authHas'

Vue.use(Vuetify)
Vue.use(VueCookies)
Vue.config.productionTip = false
Vue.prototype.$checkAuth = checkAuth
VueCookies.config('30d')
if(!VueCookies.isKey("auth")) {
    VueCookies.set('auth', 0)
} else {
    store.commit('auth/changeAuth', {auth: VueCookies.get('auth')});
}
router.beforeEach((to, from, next) => {
    if(checkAuth(to.meta.auth, VueCookies.get('auth'), -1)) {
        if(!VueCookies.isKey('testCaseId') && to.name == "Module Maintenance") {
            next('/dashboard')
            return
        } else if(to.name == "Module Maintenance") {
            store.commit('extras/setToolbarTitle', {name:VueCookies.get('testCaseId')})
        } else {
            store.commit('extras/setToolbarTitle', {name:to.name})
        }
        next()
    } else {
        if(checkAuth(VueCookies.get('auth'), [-1, 1, 2, 3, 4])) {
            if(checkAuth(VueCookies.get('auth'), [1])) {
                next('/dashboard')
            }
        } else {
            next('/')
        }
    }
})

new Vue({
    el: '#app',
    router,
    store,
    components: { App },
    template: '<App/>'
})