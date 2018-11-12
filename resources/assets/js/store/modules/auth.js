
// states
export const state = { 
    user: {}
}

// getters
export const getters = {
    name: state => state.user.name,
    auth: state => state.user.auth,
    image: state => state.user.image,
}

// actions
export const actions = {
    setUser: ({commit}) => commit('setUser', payload),
    logout: ({commit}) => commit('logout')
}

// mutations
export const mutations = {
    setUser(state, payload) {
        if(payload.user === undefined)
            state.user = {
                'auth' : '0'
            }
        else
            state.user = payload.user
    },
    logout(state) {
        state.user = {
            'auth' : '0'
        }
    }
}
