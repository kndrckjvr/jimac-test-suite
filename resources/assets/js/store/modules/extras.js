export const state = { 
    loading: false,
    baseUrl: 'http://jimac-test-suite.test/',
    toolbar_title: '',
    test_case_title: 'Test Case #'
}

// getters
export const getters = {
    loading: state => state.loading,
    baseUrl: state => state.baseUrl,
    toolbar_title: state => state.toolbar_title,
    test_case_title: state => state.test_case_title
}

// actions
export const actions = {
    loadpage: ({commit}) => commit('loadpage'),
    setToolbarTitle: ({commit}) => commit('setToolbarTitle', payload),
    setTestCaseTitle: ({commit}) => commit('setTestCaseTitle', payload)
}

// mutations
export const mutations = {
    loadpage(state) {
        state.loading = !state.loading
    },
    setToolbarTitle(state, payload) {
        state.toolbar_title = payload.name
    },
    setTestCaseTitle(state, payload) {
        state.test_case_title = payload.title
    }
}