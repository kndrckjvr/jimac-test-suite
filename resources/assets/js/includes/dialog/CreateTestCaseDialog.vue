<template>
  <v-dialog
    :value="show"
    persistent
    max-width="500">
    <v-card>
      <v-toolbar dark card color="primary">
        <v-icon>insert_drive_file</v-icon>
        <v-toolbar-title>Create Test Case</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click="closeCreateTestCaseDialog()">
            <v-icon>close</v-icon>
        </v-btn>
      </v-toolbar>
      <v-progress-linear 
        :indeterminate="true"
        height="3"
        class="ma-0"
        color="secondary lighten-1"
        :active="loading" />
      <v-card-title class="pb-0 mb-0">
        <v-form class="full-width">
          <v-container fluid grid-list-md>
            <v-layout>
              <v-text-field
                label="Test Case Title"
                :error-messages="testCaseTitleError" 
                :disabled="loading"
                v-model="$store.state.testCase.test_case_title" />
            </v-layout>
          </v-container>
        </v-form>
      </v-card-title>
      <v-card-actions>
        <v-btn
          color="primary"
          class="full-width"
          :loading="loading"
          @click="saveTestCaseName()" >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import {mapGetters} from 'vuex'
export default {
  name: 'CreateTestCaseDialog', 
  data: () => ({
    loading: false,
    testCaseTitleError: []
  }),
  methods: {
    closeCreateTestCaseDialog() {
      this.$store.commit('dialog/closeDialog', {dialog: "createTestCaseDialog"})
    },
    saveTestCaseName() {
      this.loading = true
      this.testCaseTitleError = []
      axios.post(this.baseUrl+'api/testcase/create',{
        testCaseTitle: this.testCaseTitle,
        id: this.$cookies.get('jts_token')
      }).then((res) => {
        this.loading = false
        if(res.data.status) {
          this.$cookies.set('testCaseTitle', this.testCaseTitle)
          this.$cookies.set('testCaseId', res.data.testCaseId)
          this.closeCreateTestCaseDialog()
          this.$store.commit('testCase/setTestCaseTitle', {title: this.testCaseTitle})
          this.$store.commit('testCase/setTestCaseId', {testCaseId: res.data.testCaseId})
          this.$router.push('/module')
        } else if(res.data.status == 0) {
          this.testCaseTitleError = res.data.error
        } else {
          this.$store.commit('snackbar/showSnack', {
                    "text":"Http Error!", 
                    "icon":"warning", 
                    "color":"red"
                })
        }
      }).catch((e) => {
        this.loading = false
        this.$store.commit('snackbar/showSnack', {
                    "text":"Internal Server Error!", 
                    "icon":"warning", 
                    "color":"red"
                })
      })
    }
  },
  computed: mapGetters({
    show: 'dialog/createTestCaseDialog',
    baseUrl: 'extras/baseUrl',
    testCaseTitle: 'testCase/test_case_title'
  })
}
</script>

<style>

</style>
