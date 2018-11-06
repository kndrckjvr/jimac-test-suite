export const state = { 
    shown: false,
    items:  [
        {
            title: 'Dashboard',
            icon: 'dashboard',
            to: '/dashboard',
            auth: [1, 2]
        },
        {
            title: 'Execute Tests',
            icon: 'build',
            to: '/execute',
            auth: [1, 2]
        },
        {
            title: 'Logout',
            icon: 'exit_to_app',
            click: 'logout',
            auth: [1, 2]
        },
        {
            title: 'Home',
            icon: 'home',
            to: '/',
            auth: [0]
        },
        {
            title: 'Login',
            icon: 'person',
            to: '/login',
            auth: [0]
        }
    ]
}

// getters
export const getters = {
    shown: state => state.shown,
    items: state => state.items
}

// actions
export const actions = {
    updateDrawer: ({commit}) => commit('updateDrawer')
}

// mutations
export const mutations = {
    updateDrawer(state) {
        state.shown = !state.shown
    }
}