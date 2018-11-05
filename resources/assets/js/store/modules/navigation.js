export const state = { 
    shown: false,
    items:  [
        {
            title: 'Dashboard',
            icon: 'dashboard',
            to: '/'
        },
        {
            title: 'Execute Tests',
            icon: 'build',
            to: '/execute'
        },
        {
            title: 'Logout',
            icon: 'exit_to_app',
            to: '/logout'
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