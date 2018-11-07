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
        <v-card-title class="pb-0 mb-0">
          <v-form class="full-width">
            <v-container fluid grid-list-md>
              <v-layout>
                <v-text-field
                  label="Test Case Title"
                  v-model="testCaseTitle" />
              </v-layout>
            </v-container>
          </v-form>
        </v-card-title>
        <v-card-actions>
          <v-btn
            color="primary"
            class="full-width"
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
    testCaseTitle: 'Test Case #'
  }),
  mounted() {
    axios.post(this.baseUrl+'api/testcase/getlatestid', {
      id: this.$cookies.get('jts_token')
    }).then((res) => {
      this.testCaseTitle = this.testCaseTitle + res.data.testCaseId
    })
  },
  methods: {
    closeCreateTestCaseDialog() {
      this.$store.commit('dialog/closeDialog', {dialog: "createTestCaseDialog"})
    },
    saveTestCaseName() {
      this.$cookies.set('testCaseId', this.testCaseTitle)
      this.closeCreateTestCaseDialog()
      this.$store.commit('extras/setTestCaseTitle', {title: this.testCaseTitle})
      this.$router.push('/create')
    }
  },
  computed: mapGetters({
    show: 'dialog/createTestCaseDialog',
    baseUrl: 'extras/baseUrl'
  })
}
</script>

<style>

</style>
