<template>
  <v-dialog
    :value="show"
    persistent
    max-width="600" >
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
      <v-card-title class="pa-0 mb-0" style="height: 400px; overflow: auto">
        <v-container grid-list-md fluid class="mt-0">
          <v-layout row wrap>
            <v-flex :key="i" v-for="(item, i) in storeTestCases" md12>
              <v-card class="pb-2">
                <v-toolbar dark card color="primary">
                  <v-toolbar-title>
                    <v-flex md12 class="text-md-center">
                      <h4>{{item.testCaseName}}</h4>
                    </v-flex>
                  </v-toolbar-title>
                </v-toolbar>
                <v-card-title class="pb-0 mb-0">
                  <v-container fluid grid-list-md class="ma-0 pa-0">
                    <v-layout row wrap>
                      <v-flex md6 class="text-md-center">
                        <h4>Modules: {{ item.modules }}</h4>
                      </v-flex>
                      <v-flex md6 class="text-md-center">
                        <h4>Scenarios: {{ item.testScenarios }}</h4>
                      </v-flex>
                      <v-flex md4 class="text-md-center">
                        <h5><v-icon small color="green">check_circle</v-icon> Passed: {{ item.passed }}%</h5>
                      </v-flex>
                      <v-flex md4 class="text-md-center">
                        <h5><v-icon small color="red">error</v-icon> Failed: {{ item.failed }}%</h5>
                      </v-flex>
                      <v-flex md4 class="text-md-center">
                        <h5><v-icon small color="blue">play_circle_filled</v-icon> Skipped: {{ item.skipped }}%</h5>
                      </v-flex>
                      <v-flex md6 class="text-md-center">
                        <h5><v-icon small color="yellow">access_time</v-icon> Created: {{ item.createdAt }}</h5>
                      </v-flex>
                      <v-flex md6 class="text-md-center">
                        <h5><v-icon small color="yellow">restore</v-icon> Last Updated: {{ item.updatedAt }}</h5>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-card-title>
              </v-card>
            </v-flex>
            <v-flex md12 class="text-md-center ma-0 pa-0">
              
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-title>
        <v-divider></v-divider>
      <v-card-actions>
        <h5 class="pl-1 red--text">Note: This will delete all Modules and Test Scenarios inside.</h5>
        <v-spacer></v-spacer>
        <v-btn
          color="primary"
          flat
          @click="exit()" >
          Dismiss
        </v-btn>
        <v-btn
          color="primary"
          :loading="loading"
          @click="deleteAll()" >
          Delete
        </v-btn>
        
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
  data: () => ({
    loading: false,
    toolbar_title: ''
  }),
  watch: {
    storeTestCases: function(newStoreTestCases, oldStoreTestCases) {
      if(this.storeTestCases.length > 1) {
        this.toolbar_title = "Are you sure you want to delete these Test Cases?"
      } else {
        this.toolbar_title = "Are you sure you want to delete this Test Case?"
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
