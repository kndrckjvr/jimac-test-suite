<template>
  <v-container grid-list-md fluid>
    <v-layout>
      <v-flex md12>
        <v-card>
          <v-toolbar dark card color="primary">
            <v-toolbar-title>Modules</v-toolbar-title>
          </v-toolbar>
          <v-card-title>
            <div class="full-width">
              <v-toolbar flat color="white">
                <v-btn icon color="primary" :loading="loading" @click="refreshModule()" class="mb-2"><v-icon>refresh</v-icon></v-btn>
                <v-btn icon color="primary" :loading="loading" @click="createModule()" class="mb-2"><v-icon>add</v-icon></v-btn>
                <v-btn icon color="primary" :loading="loading" @click="editModule()" class="mb-2"><v-icon>edit</v-icon></v-btn>
                <v-btn icon color="primary" :loading="loading" @click="deteleModule()" class="mb-2"><v-icon>delete_forever</v-icon></v-btn>
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
                        @click.native="toggleAll"
                      ></v-checkbox>
                    </th>
                    <th
                      v-for="header in props.headers"
                      :key="header.text"
                      :class="['column sortable', pagination.descending ? 'desc' : 'asc', header.value === pagination.sortBy ? 'active' : '']"
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
  </v-container>
</template>

<script>
import {mapGetters} from 'vuex';
export default {
  data: () => ({
    loading: false,
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
    this.$store.commit('extras/setToolbarTitle', {name:this.$cookies.get('testCaseId')})
    axios.post(this.baseUrl + 'api/module/getlatestid', {
      testCaseId: this.testCaseId
    }).then((res) => {
      this.$store.dispatch('module/moduleName', {moduleName:this.moduleName + res.data.moduleName})
    }).catch((e) => {
      this.$store.commit('snackbar/showSnack', {
                    "text":"Internal Server Error!", 
                    "icon":"warning", 
                    "color":"red"
                })
    })
    this.getData()
  },
  beforeDestroy() {
    this.$cookies.remove('testCaseId')
  },
  methods: {
    getData() {
      axios.post(this.baseUrl + 'api/module/getdata',{
        id: this.$cookies.get('jts_token'),
        testCaseId: this.testCaseId
      }).then((res)=> {
        this.loading = false
        if(res.data.status) {
          this.modules = res.data.modules
        } else {
          this.$store.commit('snackbar/showSnack', {
                    "text":"Status Error!", 
                    "icon":"warning", 
                    "color":"red"
                })
        }
      }).catch((e) => {
        this.loading = false
        this.$store.commit('snackbar/showSnack', {
                    "text":"Internal Server Error!", 
                    "icon":"warning", 
                    "color":"red"
                })
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
    testCaseId: 'testCase/testCaseId',
    moduleName: 'module/moduleName'
  })
}
</script>

<style>

</style>
