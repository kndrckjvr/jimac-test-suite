<template>
  <v-container grid-list-md fluid>
    <v-layout>
      <v-flex md12>
        <v-card>
          <v-toolbar dark card color="primary">
            <v-toolbar-title>{{ $cookies.get('testCaseTitle') }} - Modules</v-toolbar-title>
            <v-spacer></v-spacer>
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
                    @click="editTestCase()" 
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
                    @click="deleteTestCase()" 
                    class="mb-2">
                    <v-icon>delete_forever</v-icon>
                  </v-btn>
                  <span>Delete Module</span>
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
                  <span>Open Module</span>
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
        to="/module" >
        <v-icon>build</v-icon>
      </v-btn>
      <span>Back to Module Maintenance</span>
    </v-tooltip>
    <!-- <create-module-dialog></create-module-dialog> -->
  </v-container>
</template>

<script>
import { mapGetters } from 'vuex';
import CreateModuleDialog from '../includes/dialog/CreateModuleDialog';
export default {
  components: {
    CreateModuleDialog
  },
  data: () => ({
    loading: true,
    pagination: {
      sortBy: 'scenarioId'
    },
    search: '',
    headers: [
      { text: 'TS ID', value: 'scenarioId' },
      { text: 'Test Scenario Name', value: 'testScenarioName' },
      { text: 'Description', value: 'description' },
      { text: 'Status', value: 'status' },
      { text: 'Updated on', value: 'update' }
    ],
    testScenario: [],
    selected: []
  }),
  mounted() {
    this.getData()
  },
  methods: {
    openCreateModuleDialog() {
      this.$store.commit('dialog/showDialog', { dialog : "createModuleDialog" })
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
    baseUrl: 'extras/baseUrl'
  })
}
</script>

<style>

</style>
