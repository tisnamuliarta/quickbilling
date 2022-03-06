<template>
  <v-dialog v-model="dialogAttachment" persistent max-width="800px">
    <v-card>
      <v-card-title>
        <span class="font-weight-medium">
          {{ dialogTitle }}
        </span>
      </v-card-title>
      <v-card-text>
        <v-layout wrap>
          <v-row>
            <v-col cols="12" md="4" sm="12" class="mt-0">
              <dropzone
                id="attachment"
                ref="attachment"
                :options="options"
                :destroy-dropzone="true"
                @vdropzone-sending="
                  (file, xhr, formData) => sendingParams(file, xhr, formData)
                "
                @vdropzone-success="
                  (file, response) => reloadAttachment(file, response)
                "
                @vdropzone-error="
                  (file, message, xhr) => handleError(file, message, xhr)
                "
              ></dropzone>
            </v-col>

            <v-col cols="12" md="8" sm="12" class="mt-0">
              <v-data-table
                :headers="headers"
                :items="items"
                class="elevation-1"
                :loading="showLoadingAttachment"
                dense
              >
                <template #[`item.action`]="{ item }">
                  <v-tooltip top>
                    <template #activator="{ on, attrs }">
                      <v-btn icon v-bind="attrs" v-on="on">
                        <v-icon
                          small
                          class="mr-2"
                          color="orange"
                          @click="deleteFile(item)"
                        >
                          mdi-delete-circle
                        </v-icon>
                      </v-btn>
                    </template>
                    <span>Delete</span>
                  </v-tooltip>
                </template>

                <template #[`item.file_name`]="{ item }">
                  <a
                    :href="item.file_path"
                    target="_blank"
                    v-text="item.file_name"
                  ></a>
                </template>
              </v-data-table>
            </v-col>
          </v-row>
        </v-layout>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn
          color="red darken-1"
          dark
          small
          @click="dialogAttachment = false"
        >
          close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Dropzone from 'nuxt-dropzone'
import 'nuxt-dropzone/dropzone.css'

export default {
  name: 'Attachment',
  components: {
    Dropzone,
  },
  data() {
    return {
      dialogAttachment: false,
      showLoadingAttachment: false,
      dialogTitle: 'Attachment',
      options: {
        url: process.env.baseApi + '/api/attachment',
        timeout: 9000000000,
        thumbnailWidth: 50,
        thumbnailHeight: 50,
        addRemoveLinks: true,
        dictDefaultMessage:
          "<span class='mdi mdi-cloud-upload'></span> UPLOAD HERE",
        headers: {
          Authorization: this.$auth.$storage.getLocalStorage('_token.local'),
        },
      },

      headers: [
        { text: 'Attachment', value: 'file_name' },
        { text: 'Action', value: 'action' },
      ],
      items: [],
      total: 0,
      source_id: null,
      type: null,
      row: null,
    }
  },

  methods: {
    openAttachment(sourceId, type, row) {
      this.dialogAttachment = true
      this.source_id = sourceId
      this.type = type
      this.row = row

      setTimeout(() => {
        this.getAttachment()
      }, 300)
    },

    sendingParams(file, xhr, formData) {
      formData.append('source_id', this.source_id)
      formData.append('type', this.type)
    },

    handleError(file, message, xhr) {
      this.$swal({
        type: 'error',
        title: 'Oops...',
        text: message.message,
      })
    },

    reloadAttachment(file, response) {
      if (response.errors) {
        this.$swal({
          type: 'error',
          title: 'Oops...',
          text: response.message,
        })
      } else {
        this.$emit('eventCountAttachment', {
          total: response.data.count,
          row: this.row,
        })

        setTimeout(() => {
          this.getAttachment()
        }, 300)

        this.$swal({
          type: 'success',
          title: 'Success...',
          text: 'Attachment uploaded!',
        })
      }
    },

    deleteFile(item) {
      const vm = this
      this.$swal({
        title: 'Are you sure?',
        text: 'The file will be permanently deleted',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
      }).then((result) => {
        if (result.value) {
          this.$axios
            .delete(`/api/attachment`, {
              params: {
                id: item.id,
              },
            })
            .then((res) => {
              this.$swal({
                type: 'success',
                title: 'Success...',
                text: 'Attachment Deleted!',
              })
              vm.getAttachment()
            })
            .catch((err) => {
              this.$swal({
                type: 'error',
                title: 'Oops...',
                text: err.response.data.message,
              })
            })
        }
      })
    },

    getAttachment() {
      this.showLoadingAttachment = true
      const vm = this

      this.$axios
        .get(`/api/attachment`, {
          params: {
            type: vm.type,
            source_id: vm.source_id,
          },
        })
        .then((res) => {
          vm.items = res.data.data.rows
          vm.total = res.data.data.total
          vm.showLoadingAttachment = false
        })
        .catch((err) => {
          this.showLoadingAttachment = false
          this.$swal({
            type: 'error',
            title: 'Oops...',
            text: err.response.message,
          })
        })
    },
  },
}
</script>
