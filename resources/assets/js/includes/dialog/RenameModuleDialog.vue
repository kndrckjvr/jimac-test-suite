<template>
  <v-dialog
    :value="show"
    persistent
    max-width="500">
    <v-card>
      <v-toolbar dark card color="primary">
        <v-icon>description</v-icon>
        <v-toolbar-title>Rename Module</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click="closeRenameModuleNameDialog()">
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
                label="Module Name"
                :error-messages="moduleNameError" 
                :disabled="loading"
                v-model="$store.state.module.moduleName" />
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
          @click="closeRenameModuleNameDialog()">
          Dismiss
        </v-btn>
        <v-btn
          color="primary"
          :loading="loading"
          @click="renameModuleName()" >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'RenameModuleDialog', 
  data: () => ({
    loading: false,
    moduleNameError: []
  }),
  mounted() {
    this.$store.commit('testCase/setTestCaseTitle', {title : this.$cookies.get('testCaseTitle')})
  },
  methods: {
    closeRenameModuleNameDialog() {
      this.$store.commit('dialog/closeDialog', {dialog: "renameModuleDialog"})
    },
    renameModuleName() {
      this.loading = true
      this.moduleNameError = []
      axios.post(this.baseUrl+'api/module/edit',{
        moduleName: this.moduleName,
        moduleId: this.$cookies.get('moduleId')
      }).then((res) => {
        this.loading = false
        if(res.data.status) {
          
          this.$cookies.set('moduleName', this.moduleName)

          this.closeRenameModuleNameDialog()

          this.$store.commit('module/setModuleName', {moduleName: this.moduleName})

          this.$store.commit('snackbar/showSnack', {
            "text" : "Module renamed successfully", 
            "icon" : "info", 
            "color" : "green"
          })
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
    show: 'dialog/renameModuleDialog',
    baseUrl: 'extras/baseUrl',
    moduleName: 'module/moduleName'
  })
}
</script>

<style>

</style>
