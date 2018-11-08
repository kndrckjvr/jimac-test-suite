
// initial state
export const state = { 
  modules: [],
  moduleName: 'Module #',
  moduleId: '',
}

// getters
export const getters = {
  modules: state => state.modules,
  moduleName: state => state.moduleName,
  moduleId: state => state.moduleId,
}

// actions
export const actions = {
  setModules: ({commit}) => commit('setModules', payload),
  setModuleId: ({commit}) => commit('setModuleId', payload),
  setModuleName: ({commit}) => commit('setModuleName', payload),
}

// mutations
export const mutations = {
  setModules(state, payload) {
    state.modules = payload.modules
  },
  setModuleName(state, payload) {
    state.moduleName = payload.moduleName
  },
  setModuleId(state, payload) {
    state.moduleId = payload.moduleId
  },
}