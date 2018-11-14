<template>
  <v-container grid-list-md fluid>
    <v-layout>
      <v-flex md12>
        <v-card>
          <v-toolbar dark card color="primary">
            <v-toolbar-title>{{ testCaseTitle }} - Modules</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn flat dark :loading="loading" @click="editName()" class="mb-2">Edit Name</v-btn>
            <v-btn icon color="primary" :loading="loading" @click="refresh()" class="mb-2"><v-icon>refresh</v-icon></v-btn>
          </v-toolbar>
          <v-card-title>
            <div class="full-width">
              <v-toolbar flat color="white">
                <v-tooltip bottom>
                  <v-btn 
                    icon 
                    slot="activator"
                    color="primary" 
                    :disabled="loading" 
                    @click="openCreateModuleDialog()" 
                    class="mb-2">
                    <v-icon>add</v-icon>
                  </v-btn>
                  <span>Create Module</span>
                </v-tooltip>
                <v-tooltip bottom>
                  <v-btn 
                    icon 
                    slot="activator"
                    color="primary" 
                    :disabled="loading" 
                    @click="editModules()" 
                    class="mb-2">
                    <v-icon>edit</v-icon>
                  </v-btn>
                  <span>Edit Module</span>
                </v-tooltip>
                <v-tooltip bottom>
                  <v-btn 
                    icon 
                    slot="activator"
                    color="primary" 
                    :disabled="loading" 
                    @click="deleteModules()" 
                    class="mb-2">
                    <v-icon>delete_forever</v-icon>
                  </v-btn>
                  <span>Delete Module</span>
                </v-tooltip>
                <v-spacer></v-spacer>
                <v-text-field
                  v-model="search"
                  append-icon="search"
                  label="Search"
                  single-line
                  hide-details />
              </v-toolbar>
              <v-data-table
                v-model="selected"
                :headers="headers"
                :items="modules"
                :loading="loading"
                :pagination.sync="pagination"
                :search="search"
                item-key="moduleId"
                select-all
                hide-actions
                class="elevation-1" >
                <template slot="headers" slot-scope="props">
                  <tr>
                    <th>
                      <v-checkbox
                        :input-value="props.all"
                        :indeterminate="props.indeterminate"
                        primary
                        hide-details
                        @click.native="toggleAll" />
                    </th>
                    <th
                      v-for="header in props.headers"
                      :key="header.text"
                      :class="['column sortable', pagination.descending ? 'desc' : 'asc', header.value === pagination.sortBy ? 'active' : '']"
                      @click="changeSort(header.value)" >
                      <v-icon small>arrow_upward</v-icon>
                      {{ header.text }}
                    </th>
                  </tr>
                </template>
                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                <template slot="items" slot-scope="props">
                  <tr :active="props.selected" @click="props.selected = !props.selected">
                    <td>
                      <v-checkbox
                        :input-value="props.selected"
                        primary
                        hide-details
                      ></v-checkbox>
                    </td>
                    <td><strong>{{ props.item.moduleName }}</strong></td>
                    <td class="text-xs-center">{{ props.item.scenarios }}</td>
                    <td class="text-xs-center">{{ props.item.passed }}%</td>
                    <td class="text-xs-center">{{ props.item.failed }}%</td>
                    <td class="text-xs-center">{{ props.item.skipped }}%</td>
                  </tr>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                  Your search for "{{ search }}" found no results.
                </v-alert>
              </v-data-table>
            </div>
          </v-card-title>
        </v-card>
      </v-flex>
    </v-layout>
    <v-tooltip left>
      <v-btn
        color="primary"
        dark
        fixed
        bottom
        slot="activator"
        right
        fab
        to="/testcase" >
        <v-icon>build</v-icon>
      </v-btn>
      <span>Back to Test Case Maintenance</span>
    </v-tooltip>
    <create-module-dialog />
    <delete-module-dialog />
    <rename-test-case-title-dialog/>
  </v-container>
</template>

<script>
import { mapGetters } from 'vuex';
import CreateModuleDialog from '../includes/dialog/CreateModuleDialog';
import DeleteModuleDialog from '../includes/dialog/DeleteModuleDialog';
import RenameTestCaseTitleDialog from '../includes/dialog/RenameTestCaseTitleDialog';

export default {
  components: {
    CreateModuleDialog,
    DeleteModuleDialog,
    RenameTestCaseTitleDialog,
  },
  data: () => ({
    loading: true,
    pagination: {
      sortBy: 'moduleName'
    },
    search: '',
    headers: [
      { text: 'Module Name', value: 'moduleName' },
      { text: 'Number of Scenarios', value: 'scenarios' },
      { text: 'Passed', value: 'passed' },
      { text: 'Failed', value: 'failed' },
      { text: 'Skipped', value: 'skipped' }
    ],
    modules: [],
    selected: []
  }),
  mounted() {
    this.$store.commit('testCase/setTestCaseTitle', {title : this.$cookies.get('testCaseTitle')})
    axios.post(this.baseUrl + 'api/module/getlatestid', {
      testCaseId: this.$cookies.get('testCaseId')
    }).then((res) => {
      this.$store.commit('module/setModuleName', { moduleName : "Module #" + res.data.moduleId })
    }).catch((e) => {
      this.$store.commit('snackbar/showError', { "text" : "Internal Server Error!" })
    })
    this.getData()
  },
  watch: {
    storeModules(newModules, oldModules) {
      if(newModules.length == 0)
        this.getData()
    }
  },
  methods: {
    editName() {
      this.$store.commit('dialog/showDialog', {dialog: "renameTestCaseDialog"})
    },
    openCreateModuleDialog() {
      this.$store.commit('dialog/showDialog', { dialog : "createModuleDialog" })
    },
    editModules() {
      if(this.selected.length < 1) {
        this.$store.commit('snackbar/showError', { "text" : "No Test Cases Selected" })
        return
      }

      if(this.selected.length > 1) {
        //multiple edit
      } else {
        this.$store.commit('module/setModules', {'modules':this.selected[0].moduleName})
        this.$store.commit('module/setModuleId', {'moduleId':this.selected[0].moduleId})
        
        this.$cookies.set('moduleName', this.selected[0].moduleName)
        this.$cookies.set('moduleId', this.selected[0].moduleId)

        this.$router.push('/testscenario')
      }
    },
    deleteModules() {
      if(this.selected.length < 1) {
        this.$store.commit('snackbar/showError', { "text" : "No Module Selected" })
        return
      }
      this.$store.commit('module/setModules', {modules: this.selected})
      this.$store.commit('dialog/showDialog', {dialog: "deleteModuleDialog"})
    },
    refresh() {
      this.getData()
    },
    getData() {
      this.loading = true
      this.modules = []
      axios.post(this.baseUrl + 'api/module/getdata', {
        id: this.$cookies.get('token'),
        testCaseId: this.$cookies.get('testCaseId')
      }).then((res)=> {
        this.loading = false
        if(res.data.status) {
          this.modules = res.data.modules
        } else {
          this.$store.commit('snackbar/showError', { "text" : "Status Error!" })
        }
      }).catch((e) => {
        this.loading = false
        this.$store.commit('snackbar/showError', { "text" : "Internal Server Error!" })
      })
    },
    toggleAll () {
      if (this.selected.length) this.selected = []
      else this.selected = this.modules.slice()
    },
    changeSort (column) {
      if (this.pagination.sortBy === column) {
        this.pagination.descending = !this.pagination.descending
      } else {
        this.pagination.sortBy = column
        this.pagination.descending = false
      }
    }
  },
  computed: mapGetters({
    baseUrl: 'extras/baseUrl',
    testCaseId: 'testCase/testCaseId',
    testCaseTitle: 'testCase/testCaseTitle',
    moduleName: 'module/moduleName',
    storeModules: 'module/modules'
  })
}
</script>

<style>

</style>
