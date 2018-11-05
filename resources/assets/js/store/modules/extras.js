export const state = { 
    loading: false,
    baseUrl: 'http://jimac-test-suite.test/'
}

// getters
export const getters = {
    loading: state => state.loading,
    snackbar: state => state.snackbar,
    baseUrl: state => state.baseUrl
}

// actions
export const actions = {
    loadpage: ({commit}) => commit('loadpage')
}

// mutations
export const mutations = {
    loadpage(state) {
        state.loading = !state.loading
    }
}