export const state = { 
  showDialog: {
    "uploadDialog": false,
    "createTestCaseDialog": false
  }
}

// getters
export const getters = {
  uploadDialog: state => state.showDialog.uploadDialog,
  createTestCaseDialog: state => state.showDialog.createTestCaseDialog
}

// actions
export const actions = {
  showDialog: ({commit}, payload) => commit('showDialog', payload)
}

// mutations
export const mutations = {
  showDialog(state, payload) {
    state.showDialog[payload.dialog] = true
  },
  closeDialog(state, payload) {
    state.showDialog[payload.dialog] = false
  }
}