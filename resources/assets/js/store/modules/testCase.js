
// initial state
export const state = { 
  testCase: [],
  testCaseTitle: 'Test Case #'
}

// getters
export const getters = {
  testCase: state => state.testCase,
  testCaseTitle: state => state.testCaseTitle
}

// actions
export const actions = {
  setTestCase: ({commit}) => commit('setTestCase', payload),
  setTestCaseTitle: ({commit}) => commit('setTestCaseTitle', payload)
}

// mutations
export const mutations = {
  setTestCase(state, payload) {
    state.testCase = payload.testCase
  },
  setTestCaseTitle(state, payload) {
    state.testCaseTitle = payload.title
  }
}