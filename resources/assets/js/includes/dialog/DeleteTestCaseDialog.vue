<template>
  <v-dialog
    :value="show"
    persistent
    max-width="500">
    <v-card>
      <v-toolbar dark card color="primary">
        <v-icon>delete_forever</v-icon>
        <v-toolbar-title>{{ toolbar_title }}</v-toolbar-title>
      </v-toolbar>
      <v-progress-linear 
        :indeterminate="true"
        height="3"
        class="ma-0"
        color="secondary lighten-1"
        :active="loading" />
      <v-card-title class="pb-0 mb-0">
        <v-container grid-list-md fluid class="mt-0">
          <v-layout row wrap>
            <v-flex :key="i" v-for="(item, i) in storeTestCases" md12>
              <v-card>
                <v-card-title>
                  <h4>{{item.testCaseName}}</h4>
                  <!-- <p>Modules: {{ item.modules }}</p>
                  <p>Created: {{ item.createdBy.date }}</p> -->
                </v-card-title>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-flex class="ma-1">
          <v-btn
            color="primary"
            :loading="loading"
            @click="deleteAll()" >
            Delete
          </v-btn>
          <v-btn
            color="primary"
            @click="exit()" >
            Dismiss
        </v-btn>
        </v-flex>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapGetters } from 'vuex';
import TestCaseMaintenance from '../../components/TestCaseMaintenance';
export default {
  data: () => ({
    loading: false,
    toolbar_title: ''
  }),
  watch: {
    storeTestCases: function(newStoreTestCases, oldStoreTestCase) {
      if(this.storeTestCases.length > 1) {
        this.toolbar_title = "Are you sure to delete these Test Cases?"
      } else {
        this.toolbar_title = "Are you sure to delete this Test Case?"
      }
    }
  },
  methods: {
    deleteAll() {
      var testCaseId = []
      for(var testCase in this.storeTestCases) {
        testCaseId.push(this.storeTestCases[testCase].testCaseId)
      }
      console.log(testCaseId)
      axios.post(this.baseUrl + 'api/testcase/delete',{
        testCaseId: testCaseId
      }).then((res) => {
        if(res.data.status) {
          this.$store.commit('testCase/setTestCase', {testCase: []})
          this.$store.commit('dialog/closeDialog', {dialog: "deleteTestCaseDialog"})
        }
      }).catch((e) => {
        console.log(e)
      })
    },
    exit() {
      this.$store.commit('dialog/closeDialog', {dialog: "deleteTestCaseDialog"})
    }
  },
  computed: mapGetters({
    baseUrl: 'extras/baseUrl',
    show: 'dialog/deleteTestCaseDialog',
    storeTestCases: 'testCase/testCase'
  })
}
</script>

<style>

</style>
