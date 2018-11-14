<template ref="testcasemaintenance">
    <v-container fluid grid-list-md>
        <v-layout>
            <v-flex md12>
                <v-card>
                    <v-toolbar dark card color="primary">
                        <v-toolbar-title>Test Cases</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn icon color="primary" :loading="loading" @click="refresh()" class="mb-2"><v-icon>refresh</v-icon></v-btn>
                        <v-btn flat>Download<v-icon right>cloud_download</v-icon></v-btn>
                        <v-btn flat @click="openUploadDialog()">Upload<v-icon right>cloud_upload</v-icon></v-btn>
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
                                @click="openCreateTestCaseDialog()" 
                                class="mb-2">
                                <v-icon>add</v-icon>
                              </v-btn>
                              <span>Create Test Case</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                              <v-btn 
                                icon 
                                slot="activator"
                                color="primary" 
                                :disabled="loading" 
                                @click="editTestCase()" 
                                class="mb-2">
                                <v-icon>edit</v-icon>
                              </v-btn>
                              <span>Edit Test Case</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                              <v-btn 
                                icon 
                                slot="activator"
                                color="primary" 
                                :disabled="loading" 
                                @click="deleteTestCase()" 
                                class="mb-2">
                                <v-icon>delete_forever</v-icon>
                              </v-btn>
                              <span>Delete Test Case</span>
                            </v-tooltip>
                            <v-tooltip bottom>
                              <v-btn 
                                icon 
                                slot="activator"
                                color="primary" 
                                :disabled="loading" 
                                @click="openTestCase()" 
                                class="mb-2">
                                <v-icon>arrow_forward</v-icon>
                              </v-btn>
                              <span>Execute Test Case</span>
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
                            :items="testCases"
                            :loading="loading"
                            :pagination.sync="pagination"
                            :search="search"
                            item-key="testCaseId"
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
                                    @click.native="toggleAll"
                                  ></v-checkbox>
                                </th>
                                <th
                                  v-for="header in props.headers"
                                  :key="header.text"
                                  :class="['column sortable', pagination.descending ? 'desc' : 'asc', 
                                  header.value === pagination.sortBy ? 'active' : '']"
                                  @click="changeSort(header.value)"
                                >
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
                                <td><strong>{{ props.item.testCaseName }}</strong></td>
                                <td class="text-xs-center">{{ props.item.modules }}</td>
                                <td class="text-xs-center">{{ props.item.testScenarios }}</td>
                                <td class="text-xs-center">{{ props.item.passed }}%</td>
                                <td class="text-xs-center">{{ props.item.failed }}%</td>
                                <td class="text-xs-center">{{ props.item.skipped }}%</td>
                                <td class="text-xs-center">{{ props.item.updatedAt }}</td>
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
        <upload-dialog/>
        <create-test-case-dialog/>
        <delete-test-case-dialog/>
    </v-container>
</template>

<script>
import UploadDialog from '../includes/dialog/UploadDialog'
import CreateTestCaseDialog from '../includes/dialog/CreateTestCaseDialog'
import DeleteTestCaseDialog from '../includes/dialog/DeleteTestCaseDialog'
import { mapGetters } from 'vuex';

export default {
  components: {
    UploadDialog,
    CreateTestCaseDialog,
    DeleteTestCaseDialog,
  },
  data: () => ({
      pagination: {
        sortBy: 'createdBy'
      },
      totalTestCases: 0,
      search: '',
      testCases: [],
      selected: [],
      loading: true,
      pagination: {},
      headers: [
        { text: 'Test Case Name', value: 'testCaseName' },
        { text: 'Number of Modules', value: 'modules' },
        { text: 'Number of Scenarios', value: 'testScenarios' },
        { text: 'Passed', value: 'passed' },
        { text: 'Failed', value: 'failed' },
        { text: 'Skipped', value: 'skipped' },
        { text: 'Last Updated', value: 'updatedAt'}
      ],
  }),
  mounted() {
    // Test Cases
    this.$cookies.remove('testCaseId')
    this.$cookies.remove('testCaseTitle')

    // Modules
    this.$cookies.remove('moduleId')
    this.$cookies.remove('moduleName')

    // Test Scenarios
    this.$cookies.remove('testScenarioId')
    this.$cookies.remove('testScenarioName')
    this.getData()
  },
  watch: {
    storeTestCases(newTestCases, oldTestCases) {
      if(newTestCases.length == 0)
        this.getData()
    }
  },
  methods: {
    openCreateTestCaseDialog() {
      this.$store.commit('dialog/showDialog', {dialog: "createTestCaseDialog"})
    },
    openUploadDialog() {
      this.$store.commit('dialog/showDialog', {dialog: "uploadDialog"})
    },
    editTestCase() {
      if(this.selected.length < 1) {
        this.$store.commit('snackbar/showError', { "text" : "No Test Cases Selected" })
        return
      }

      if(this.selected.length > 1) {
        //multiple edit
      } else {
        this.$store.commit('testCase/setTestCaseTitle', {'testCase':this.selected[0].testCaseName})
        this.$store.commit('testCase/setTestCaseId', {'testCase':this.selected[0].testCaseId})
        
        this.$cookies.set('testCaseTitle', this.selected[0].testCaseName)
        this.$cookies.set('testCaseId', this.selected[0].testCaseId)

        this.$router.push('/module')
      }
    },
    deleteTestCase() {
      if(this.selected.length < 1) {
        this.$store.commit('snackbar/showError', { "text" : "No Test Case Selected" })
        return
      }
      this.$store.commit('testCase/setTestCase', {testCase: this.selected})
      this.$store.commit('dialog/showDialog', {dialog: "deleteTestCaseDialog"})
    },
    refresh() {
      this.getData()
    },
    getData() {
      this.loading = true
      this.testCases = []
      this.selected = []

      axios.post(this.baseUrl + 'api/testcase/getdata', {
        token: this.$cookies.get('token')
      }).then((res)=> {
        this.$store.commit('testCase/setTestCaseTitle', {title: "Test Case #" + res.data.testCaseId})
        this.loading = false
        if(res.data.status) {
          this.testCases = res.data.testCases
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
      else this.selected = this.testCases.slice()
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
    storeTestCases: 'testCase/testCase'
  })
}
</script>
