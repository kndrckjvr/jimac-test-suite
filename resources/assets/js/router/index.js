import Vue from 'vue'
import Router from 'vue-router'
import Home from '../components/Home'
import Login from '../components/Login'
import Dashboard from '../components/Dashboard'
import TestCaseMaintenance from '../components/TestCaseMaintenance'
import ModuleMaintenance from '../components/ModuleMaintenance'
import TestScenarioMaintenance from '../components/TestScenarioMaintenance'

Vue.use(Router)

export default new Router({
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return {
        selector: to.hash
        // , offset: { x: 0, y: 10 }
      }
    }
    return savedPosition ? savedPosition : { x: 0, y: 0 }
  },

  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home,
      meta: {
        auth: [0]
      }
    },
    {
      path: '/login',
      name: 'Login',
      component: Login,
      meta: {
        auth: [0]
      }
    },
    {
      path: '/dashboard',
      //path: '/',
      name: 'Dashboard',
      component: Dashboard,
      meta: {
        auth: [1, 2]
      }
    },
    {
      path: '/testcase',
      name: 'Test Case Maintenance',
      component: TestCaseMaintenance,
      meta: {
        auth: [1, 2]
      }
    },
    {
      path: '/module',
      name: 'Module Maintenance',
      component: ModuleMaintenance,
      meta: {
        auth: [1, 2]
      }
    },
    {
      path: '/testscenario',
      name: 'Test Scenario Maintenance',
      component: TestScenarioMaintenance,
      meta: {
        auth: [1, 2]
      }
    }
  ]
})