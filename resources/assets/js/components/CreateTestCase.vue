<template>
  <v-container grid-list-md fluid>
    <v-layout>
      <v-flex md12>
        <v-card>
          <v-toolbar dark card color="primary">
            <v-toolbar-title>{{ $cookies.get('testCaseId') }}</v-toolbar-title>
          </v-toolbar>
          <v-card-title>
            <div class="full-width">
              <v-toolbar flat color="white">
                <v-toolbar-title>Modules</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon color="primary" class="mb-2"><v-icon>refresh</v-icon></v-btn>
                <v-btn icon color="primary" class="mb-2"><v-icon>add</v-icon></v-btn>
              </v-toolbar>
              <v-data-table
                :headers="headers"
                :items="modules"
                hide-actions
                class="elevation-1" >
                <template slot="items" slot-scope="props">
                  <td><strong>{{ props.item.moduleName }}</strong></td>
                  <td class="text-xs-center">{{ props.item.scenarios }}</td>
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
    </v-layout>
  </v-container>
</template>

<script>
import {mapGetters} from 'vuex';
export default {
  data: () => ({
    headers: [
      { text: 'Module Name', value: 'moduleName' },
      { text: 'Number of Scenarios', value: 'scenarios' },
      { text: 'Passed', value: 'passed' },
      { text: 'Failed', value: 'failed' },
      { text: 'Skipped', value: 'skipped' },
      { text: 'Actions', value: 'moduleId', sortable: false }
    ],
    modules: [
      {
        moduleName: 'Item Maintenance',
        scenarios: '1',
        passed: '100%',
        failed: '0%',
        skipped: '0%',
        moduleId: '1'
      }
    ]
  }),
  mounted() {

  },
  computed: mapGetters({
    test_case_title: 'extras/test_case_title'
  })
}
</script>

<style>

</style>
