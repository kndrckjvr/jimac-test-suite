export const state = { 
    userAuth: ""
}

// getters
export const getters = {
    userAuth: state => state.userAuth
}

// actions
export const actions = {
    changeAuth: ({commit}) => commit('changeAuth', payload)
}

// mutations
export const mutations = {
    changeAuth(state, payload) {
        state.userAuth = payload.auth
    }
}
