export const state = { 
  showDialog: {
    "uploadDialog": false,
    "createTestCaseDialog": false,
    "deleteTestCaseDialog": false,
    "createModuleDialog": false,
    "deleteModuleDialog": false,
    "renameTestCaseDialog": false,
    "renameModuleDialog": false,
    "createTestScenarioDialog": false
  }
}

// getters
export const getters = {
  uploadDialog: state => state.showDialog.uploadDialog,
  createTestCaseDialog: state => state.showDialog.createTestCaseDialog,
  deleteTestCaseDialog: state => state.showDialog.deleteTestCaseDialog,
  createModuleDialog: state => state.showDialog.createModuleDialog,
  deleteModuleDialog: state => state.showDialog.deleteModuleDialog,
  renameTestCaseDialog: state => state.showDialog.renameTestCaseDialog,
  renameModuleDialog: state => state.showDialog.renameModuleDialog,
  createTestScenarioDialog: state => state.showDialog.createTestScenarioDialog
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