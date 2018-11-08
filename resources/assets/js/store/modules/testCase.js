
// initial state
export const state = { 
  testCase: [],
  test_case_title: 'Test Case #',
  testCaseId: ''
}

// getters
export const getters = {
  testCase: state => state.testCase,
  test_case_title: state => state.test_case_title,
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
    state.test_case_title = payload.title
  },
  setTestCaseId(state, payload) {
    state.testCaseId = payload.testCaseId
  }
}