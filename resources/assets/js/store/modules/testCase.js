
// initial state
export const state = { 
  testCase: [],
  testCaseTitle: 'Test Case #',
  testCaseId: ''
}

// getters
export const getters = {
  testCase: state => state.testCase,
  testCaseTitle: state => state.testCaseTitle,
  testCaseId: state => state.testCaseId
}

// actions
export const actions = {
  setTestCase: ({commit}) => commit('setTestCase', payload),
  setTestCaseTitle: ({commit}) => commit('setTestCaseTitle', payload),
  setTestCaseId: ({commit}) => commit('setTestCaseId', payload)
}

// mutations
export const mutations = {
  setTestCase(state, payload) {
    state.testCase = payload.testCase
  },
  setTestCaseTitle(state, payload) {
    state.testCaseTitle = payload.title
  },
  setTestCaseId(state, payload) {
    state.testCaseId = payload.testCaseId
  }
}