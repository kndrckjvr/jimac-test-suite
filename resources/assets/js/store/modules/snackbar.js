
// initial state
export const state = { 
    show: false,
    text: '',
    color: '',
    icon: ''
}

// getters
export const getters = {
    show: state => state.show,
    text: state => state.text,
    color: state => state.color,
    icon: state => state.icon
}

// actions
export const actions = {
    showSnack: ({commit}) => commit("showSnack", payload),
    hideSnack: ({commit}) => commit("hideSnack")
}

// mutations
export const mutations = {
    showSnack(state, payload) {
        state.show = true
        setTimeout(() => { state.show = false }, 3000)
        state.text = payload.text
        state.icon = payload.icon
        state.color = payload.color
    },
    hideSnack(state) {
        state.show = false
    }
}