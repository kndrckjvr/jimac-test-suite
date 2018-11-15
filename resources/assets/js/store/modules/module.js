
// initial state
export const state = { 
  modules: [],
  moduleName: 'Module #',
}

// getters
export const getters = {
  modules: state => state.modules,
  moduleName: state => state.moduleName,
}

// actions
export const actions = {
  setModules: ({commit}) => commit('setModules', payload),
  setModuleName: ({commit}) => commit('setModuleName', payload),
}

// mutations
export const mutations = {
  setModules(state, payload) {
    state.modules = payload.modules
  },
  setModuleName(state, payload) {
    state.moduleName = payload.moduleName
  }
}