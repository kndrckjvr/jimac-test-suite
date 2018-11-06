export const state = { 
    loading: false,
    baseUrl: 'http://jimac-test-suite.test/',
    toolbar_title: ''
}

// getters
export const getters = {
    loading: state => state.loading,
    baseUrl: state => state.baseUrl,
    toolbar_title: state => state.toolbar_title
}

// actions
export const actions = {
    loadpage: ({commit}) => commit('loadpage'),
    setToolbarTitle: ({commit}) => commit('setToolbarTitle', payload)
}

// mutations
export const mutations = {
    loadpage(state) {
        state.loading = !state.loading
    },
    setToolbarTitle(state, payload) {
        state.toolbar_title = payload.name
    }
}