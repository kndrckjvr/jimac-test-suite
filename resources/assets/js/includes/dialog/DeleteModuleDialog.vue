<template>
  <v-dialog
    :value="show"
    persistent
    max-width="600">
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
            <v-flex :key="i" v-for="(item, i) in storeModules" md12>
              <v-card class="pb-2">
                <v-toolbar dark card color="primary">
                  <v-toolbar-title>
                    <v-flex md12 class="text-md-center">
                      <h4>{{item.moduleName}}</h4>
                    </v-flex>
                  </v-toolbar-title>
                </v-toolbar>
                <v-card-title class="pb-0 mb-0">
                  <v-container fluid grid-list-md class="ma-0 pa-0">
                    <v-layout row wrap>
                      <v-flex md12>
                        <h4 class="text-md-center">Test Scenarios: {{ item.scenarios }}</h4>
                      </v-flex>
                      <v-flex md4>
                        <h5 class="text-md-center"><v-icon small color="green">check_circle</v-icon> Passed: {{ item.passed }}%</h5>
                      </v-flex>
                      <v-flex md4>
                        <h5 class="text-md-center"><v-icon small color="red">error</v-icon> Failed: {{ item.failed }}%</h5>
                      </v-flex>
                      <v-flex md4>
                        <h5 class="text-md-center"><v-icon small color="blue">play_circle_filled</v-icon> Skipped: {{ item.skipped }}%</h5>
                      </v-flex>
                    </v-layout>
                  </v-container>
                  <!-- <p>Modules: {{ item.modules }}</p>
                  <p>Created: {{ item.createdBy.date }}</p> -->
                </v-card-title>
              </v-card>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-actions>
        <h5 class="pl-1 red--text">Note: This will delete all the Test Scenarios inside.</h5>
        <v-spacer></v-spacer>
        <v-btn
          flat
          color="primary"
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
    storeModules: function(newStoreModules, oldStoreModules) {
      if(this.storeModules.length > 1) {
        this.toolbar_title = "Are you sure you want to delete these Modules?"
      } else {
        this.toolbar_title = "Are you sure you want to delete this Module?"
      }
    }
  },
  methods: {
    deleteAll() {
      var moduleId = []

      for(var module in this.storeModules)
        moduleId.push(this.storeModules[module].moduleId)

      axios.post(this.baseUrl + 'api/module/delete',{
        moduleId: moduleId
      }).then((res) => {
        if(res.data.status) {      
          axios.post(this.baseUrl + 'api/module/getlatestid', {
            testCaseId: this.$cookies.get('testCaseId')
          }).then((res) => {
            this.$store.commit('module/setModuleName', { moduleName : "Module #" + res.data.moduleId })
          }).catch((e) => {
            this.$store.commit('snackbar/showError', { "text" : "Internal Server Error!" })
          })
          this.$store.commit('module/setModules', {modules: []})
          this.$store.commit('dialog/closeDialog', {dialog: "deleteModuleDialog"})
        }
      }).catch((e) => {
        console.log(e)
      })
    },
    exit() {
      this.$store.commit('dialog/closeDialog', {dialog: "deleteModuleDialog"})
    }
  },
  computed: mapGetters({
    baseUrl: 'extras/baseUrl',
    show: 'dialog/deleteModuleDialog',
    storeModules: 'module/modules'
  })
}
</script>

<style>

</style>
