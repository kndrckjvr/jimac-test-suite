<template>
  <v-dialog
    :value="show"
    persistent
    max-width="500">
    <v-card>
      <v-toolbar dark card color="primary">
        <v-icon>cloud_upload</v-icon>
        <v-toolbar-title>Upload Test Case</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon @click="closeUploadDialog()" :disabled="loading">
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
                <input
                  type="file"
                  style="display: none"
                  ref="fileUploader"
                  accept=".xls,.xlsx"
                  @change="attachFile" >
                <v-flex md9>
                  <transition name="fade" mode="out-in" @beforeLeave="beforeLeave" @enter="enter" @afterEnter="afterEnter">
                    <v-btn
                      class="full-width"
                      v-if="!fileName"
                      @click="$refs.fileUploader.click()" >
                      <v-icon>attach_file</v-icon> Attach File
                    </v-btn>
                    <v-text-field
                      label="File"
                      v-model="fileName"
                      prepend-icon="attach_file"
                      v-if="fileName"
                      />
                  </transition>
                </v-flex>
                <v-flex md3>
                  <v-btn
                    :loading="loading"
                    color="primary"
                    :disabled="enableUpload"
                    @click="uploadFile()"
                    class="full-width" >
                    Upload
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-form>
        </v-card-title>
        <v-card-actions>
          <v-btn
            color="primary"
            v-if="fileName"
            @click="$refs.fileUploader.click()"
            class="full-width" >
            Change Uploaded File
          </v-btn>
        </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import {mapGetters} from 'vuex'
export default {
  name: 'UploadDialog',
  data: () => ({
    loading: false,
    fileName: '',
    file: null,
    enableUpload: true
  }),
  methods: {
    closeUploadDialog() {
      this.$store.commit('dialog/closeDialog', {dialog: "uploadDialog"})
    },
    attachFile(e) {
      this.fileName = this.$refs.fileUploader.files[0].name
      this.file = this.$refs.fileUploader.files[0]
      this.enableUpload = false
    },
    uploadFile() {
      let formData = new FormData()
      formData.append('file', this.file)
      formData.append('id', 1)
      axios.post(baseUrl + '/api/testcase/upload', formData)
        .then((res) => {
          this.loading = false
        }).catch((e) => {
          this.loading = false
          this.$store.commit('snackbar/showSnack', {
            "text": "Internal Server Error", 
            "icon":"warning", 
            "color":"red"
          })
        })
    },
    beforeLeave(element) {
      this.prevHeight = getComputedStyle(element).height;
    },
    enter(element) {
      const { height } = getComputedStyle(element);
      element.style.height = this.prevHeight;
      setTimeout(() => {
          element.style.height = height;
      });
    },
    afterEnter(element) {
      element.style.height = 'auto';
    }
  },
  computed: mapGetters({
    show: 'dialog/uploadDialog'
  })
}
</script>

<style>

</style>
