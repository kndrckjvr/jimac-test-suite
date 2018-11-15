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
                v-model="$store.state.testCase.testCaseTitle" />
            </v-layout>
          </v-container>
        </v-form>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          flat
          :loading="loading"
          @click="closeDialog()">
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
        token: this.$cookies.get('token')
      }).then((res) => {
        this.loading = false
        if(res.data.status) {
          this.$cookies.set('testCaseTitle', this.testCaseTitle)
          this.$cookies.set('testCaseId', res.data.testCaseId)

          this.closeCreateTestCaseDialog()

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
    show: 'dialog/createTestCaseDialog',
    baseUrl: 'extras/baseUrl',
    testCaseTitle: 'testCase/testCaseTitle'
  })
}
</script>

<style>

</style>
