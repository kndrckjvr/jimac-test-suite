<template>
    <v-container fluid grid-list-md>
        <v-layout>
            <v-flex md8>
                <v-card>
                    <v-toolbar dark card color="primary">
                        <v-toolbar-title>Test Cases</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn flat @click="openUploadDialog()">Upload<v-icon right>cloud_upload</v-icon></v-btn>
                    </v-toolbar>
                    <v-card-title>
                        <div class="full-width">
                          <v-toolbar flat color="white">
                            <v-spacer></v-spacer>
                            <v-btn icon color="primary" :loading="loading" class="mb-2"><v-icon>refresh</v-icon></v-btn>
                            <v-btn icon color="primary" :loading="loading" @click="openCreateTestCaseDialog()" class="mb-2"><v-icon>add</v-icon></v-btn>
                          </v-toolbar>
                          <v-data-table
                            :headers="headers"
                            :items="testCases"
                            :loading="loading"
                            hide-actions
                            class="elevation-1" >
                            <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                            <template slot="items" slot-scope="props">
                              <td><strong>{{ props.item.testCaseName }}</strong></td>
                              <td class="text-xs-center">{{ props.item.modules }}</td>
                              <td class="text-xs-center">{{ props.item.passed }}</td>
                              <td class="text-xs-center">{{ props.item.failed }}</td>
                              <td class="text-xs-center">{{ props.item.skipped }}</td>
                              <td class="justify-center px-0">
                                <v-icon
                                  small
                                  class="mr-2"
                                  @click="editItem(props.item)" >
                                  edit
                                </v-icon>
                                <v-icon
                                  small
                                  @click="deleteItem(props.item)" >
                                  delete
                                </v-icon>
                              </td>
                            </template>
                          </v-data-table>
                        </div>
                    </v-card-title>
                </v-card>
            </v-flex>
            <v-flex md4>
                <v-card>
                    <v-toolbar dark card color="primary">
                        <v-toolbar-title>Templates</v-toolbar-title>
                    </v-toolbar>
                    <v-card-actions>
                        <v-btn 
                            class="full-width"
                            color="primary">
                            Download XLS Template<v-icon right>cloud_download</v-icon>
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
        <upload-dialog/>
        <create-test-case-dialog/>
    </v-container>
</template>

<script>
import UploadDialog from '../includes/dialog/UploadDialog'
import CreateTestCaseDialog from '../includes/dialog/CreateTestCaseDialog'
import { mapGetters } from 'vuex';

export default {
  components: {
    UploadDialog,
    CreateTestCaseDialog
  },
  data: () => ({
      totalDesserts: 0,
      testCases: [
        {
          testCaseName: 'Test Case #1',
          modules: '1',
          passed: '100%',
          failed: '0%',
          skipped: '0%',
          testCaseId: '1'
        }
      ],
      loading: false,
      pagination: {},
      headers: [
        { text: 'Test Case Name', value: 'testCaseName' },
        { text: 'Number of Modules', value: 'modules' },
        { text: 'Passed', value: 'passed' },
        { text: 'Failed', value: 'failed' },
        { text: 'Skipped', value: 'skipped' },
        { text: 'Actions', value: 'testCaseId', sortable: false }
      ],
  }),
  mounted() {

  },
  methods: {
    openCreateTestCaseDialog() {
      this.$store.commit('dialog/showDialog', {dialog: "createTestCaseDialog"})
    },
    openUploadDialog() {
      this.$store.commit('dialog/showDialog', {dialog: "uploadDialog"})
    },
    getData() {
      axios.post(this.baseUrl + 'api/testcase/getdata',{
        id: this.$cookies.get('jts_token')
      }).then((res)=> {

      })
    }
  },
  computed: mapGetters({
    baseUrl: 'extras/baseUrl'
  })
}
</script>
