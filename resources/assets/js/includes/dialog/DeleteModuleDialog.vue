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
            <v-flex :key="i" v-for="(item, i) in storeModules" md12>
              <v-card>
                <v-card-title>
                  <h4>{{item.moduleName}}</h4>
                  <!-- <p>Modules: {{ item.modules }}</p>
                  <p>Created: {{ item.createdBy.date }}</p> -->
                </v-card-title>
              </v-card>
            </v-flex>
            <v-flex md12>
              <h4 class="red--text">Note: This will delete all the Test Scenarios inside.</h4>
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
export default {
  data: () => ({
    loading: false,
    toolbar_title: ''
  }),
  watch: {
    storeModules: function(newStoreModules, oldStoreModules) {
      if(this.storeModules.length > 1) {
        this.toolbar_title = "Are you sure to delete these Modules?"
      } else {
        this.toolbar_title = "Are you sure to delete this Module?"
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
            testCaseId: this.testCaseId
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
