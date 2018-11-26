<template>
  <v-dialog
    :value="show"
    persistent
    scrollable
    max-width="800">
    <v-card>
      <v-toolbar dark card color="primary">
        <v-icon>view_day</v-icon>
        <v-toolbar-title>Create Test Scenario</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click="closeCreateTestScenarioDialog()">
            <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-progress-linear 
        :indeterminate="true"
        height="3"
        class="ma-0"
        color="secondary lighten-1"
        :active="loading" />
      <v-card-title>
        <v-container fluid grid-list-md class="pa-0 ma-0">
          <v-layout row wrap>
            <v-flex md12>
              <v-text-field
                label="Test Scenario Title"
                :error-messages="testScenarioTitle"
                :disabled="loading"
                v-model="testScenario" />  
            </v-flex>
             <v-flex md6>
              <v-textarea
                outline
                no-resize
                v-model="expectedResult"
                :error-messages="expectedError"
                label="Expected Result"/>
            </v-flex>
            <v-flex md6>
              <v-textarea
                outline
                no-resize
                v-model="testSteps"
                :error-messages="testStepsError"
                label="Test Steps"/>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          :loading="loading"
          @click="closeCreateTestScenarioDialog()"
          flat>
          Dismiss
        </v-btn>
        <v-btn
          color="primary"
          :loading="loading"
          @click="saveTestCaseName()" >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'CreateTestScenarioDialog', 
  data: () => ({
    loading: false,
    testScenarioTitle: '',
    expectedResult: '',
    expectedError: [],
    testSteps: '',
    testStepsError: []
  }),
  mounted() {

  },
  methods: {
    closeCreateTestScenarioDialog() {
      this.$store.commit('dialog/closeDialog', {dialog: "createTestScenarioDialog"})
    },
    saveTestCaseName() {
      this.loading = true
      this.testCaseTitleError = []
      axios.post(this.baseUrl+'api/testscenario/create',{
        testCaseTitle: this.testCaseTitle,
        token: this.$cookies.get('token')
      }).then((res) => {
        this.loading = false
        if(res.data.status) {
          this.$cookies.set('testCaseTitle', this.testCaseTitle)
          this.$cookies.set('testCaseId', res.data.testCaseId)

          this.CreateTestScenarioDialog()

          this.$store.commit('testCase/setTestCaseTitle', {title: this.testCaseTitle})

          this.$router.push('/module')
        } else if(res.data.status == 0) {
          this.testCaseTitleError = res.data.error
        } else {
          this.$store.commit('snackbar/showError', { "text" : "Http Error!" })
        }
      }).catch((e) => {
        this.loading = false
        this.$store.commit('snackbar/showError', { "text":"Internal Server Error!" })
      })
    }
  },
  computed: mapGetters({
    show: 'dialog/createTestScenarioDialog',
    baseUrl: 'extras/baseUrl',
    testCaseTitle: 'testCase/testCaseTitle'
  })
}
</script>

<style>

</style>
