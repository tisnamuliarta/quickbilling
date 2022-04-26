<template>
  <v-row>
    <v-col cols="12" :md="itemAction.length > 0 ? '10' : '12'">
      <DocumentFormWindow
        ref="formWindow"
        :breadcrumb="breadcrumb"
        @getDataFromApi="getDataFromApi"
      >
        <template #action-toolbar>
          <v-btn text small @click="arrowLink('prev', form.type)">
            <v-icon>mdi-arrow-left</v-icon>
            <span>Prev</span>
          </v-btn>
          <v-btn text small @click="arrowLink('next', form.type)">
            <v-icon>mdi-arrow-right</v-icon>
            <span>Next</span>
          </v-btn>

          <v-btn icon small @click="$refs.dialogAudit.openDialogAudit(audits)">
            <v-icon>mdi-history</v-icon>
          </v-btn>

          <v-btn color="green" dark icon small @click="getDataFromApi">
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </template>

        <template #content>
          <v-row v-show="showLoading" no-gutters>
            <v-col cols="12">
              <v-skeleton-loader
                type="list-item-three-line, table-thead, table-tbody, list-item-three-line"
              ></v-skeleton-loader>
            </v-col>
          </v-row>
          <LazyDocumentFormInput v-show="!showLoading" ref="formDocument"></LazyDocumentFormInput>
        </template>

        <template #action>
          <v-menu
            offset-y
          >
            <template v-slot:activator="{ attrs, on }">
              <v-btn
                class="white--text"
                color="green"
                :disabled="checkDisable()"
                v-bind="attrs"
                small
                v-on="on"
              >
                Print & Preview
                <v-icon
                  right
                  dark
                >
                  mdi-printer
                </v-icon>
              </v-btn>
            </template>

            <v-list dense>
              <v-list-item
                v-for="item in items"
                :key="item.title"
                dense
                link
                @click="printAction(item.action)"
              >
                <v-list-item-icon>
                  <v-icon v-text="item.icon"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title v-text="item.title"></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-menu>

          <v-menu
            offset-y
          >
            <template v-slot:activator="{ attrs, on }">
              <v-btn
                class="white--text ml-5"
                color="green"
                :disabled="checkDisable()"
                v-bind="attrs"
                small
                v-on="on"
              >
                Action
                <v-icon
                  right
                  dark
                >
                  mdi-folder-cog
                </v-icon>
              </v-btn>
            </template>

            <v-list>
              <v-list-item
                v-for="item in itemAction"
                :key="item.title"
                dense
                link
                @click="actionDocument(item.action)"
              >
                <v-list-item-icon>
                  <v-icon v-text="item.icon"></v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title v-text="item.title"></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-menu>
        </template>

        <template #saveData>
          <v-btn
            color="green darken-1"
            class="white--text"
            :disabled="checkDisable()"
            small
            @click="store"
          >
            {{ actionName }}
            <v-icon>
              mdi-check
            </v-icon>
          </v-btn>
        </template>
      </DocumentFormWindow>
    </v-col>
    <v-col v-if="itemAction.length > 0" cols="12" md="2">
      <v-subheader style="margin-top: -20px">Action</v-subheader>
      <DocumentCardAction ref="documentAction"/>
    </v-col>

    <LazyDocumentDialogSendEmail ref="dialogSendEmail"></LazyDocumentDialogSendEmail>

    <LazyDocumentDialogAudit ref="dialogAudit"/>

    <LazySpinnerLoading
      v-if='dialogLoading'
      ref='spinnerLoadingImport'
    ></LazySpinnerLoading>
  </v-row>
</template>

<script>
export default {
  name: 'FormDocument',

  layout: 'dashboard',

  data() {
    return {
      breadcrumb: [],
      form: {},
      audits: {},
      defaultItem: {},
      url: '/api/documents/form',
      dialogLoading: false,
      showLoading: false,
      itemAction: [],
      actionName: 'Save',
      items: [
        {title: 'Preview', action: 'preview', icon: 'mdi-printer'},
        {title: 'Send Email', action: 'sendEmail', icon: 'mdi-email'},
      ],
    }
  },

  head() {
    return {
      title: 'Form Document',
    }
  },

  created() {
    this.getDataFromApi()
  },

  methods: {
    checkDisable() {
      return this.form.status === 'closed' || this.form.status === 'cancel';
    },

    arrowLink(status, type) {
      this.$axios.get(this.url + '/arrow', {
        params: {
          type,
          status,
          document: this.$route.query.document
        }
      })
        .then(res => {
          this.$router.push({
            path: '/dashboard/documents/form',
            query: {
              document: res.data.data.id,
              type
            }
          })

          setTimeout(() => {
            this.getDataFromApi()
          }, 300)
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    getDataFromApi(copyFromId) {
      // this.dialogLoading = true
      this.showLoading = true
      const type = this.$route.query.type
      this.$axios
        .get(this.url + '/' + this.$route.query.document, {
          params: {
            type,
            copyFromId,
          },
        })
        .then((res) => {
          let form = ''
          this.audits = res.data.data.audits
          if (res.data.data.count > 0) {
            form = res.data.data.rows
            this.actionName = 'Update'
          } else {
            form = res.data.data.form
            this.actionName = 'Save'
          }

          this.form = Object.assign({}, form)
          this.defaultItem = Object.assign({}, form)

          if (res.data.data.count > 0) {
            this.itemAction = res.data.data.action
            setTimeout(() => {
              this.$refs.documentAction.setAction(this.itemAction, this.checkDisable())
            }, 50)
          }
          this.getBreadcrumb(type, form, form.status)

          setTimeout(() => {
            this.$refs.formDocument.setData(this.form)
          }, 30)
        })
        .catch((err) => {
          const message = (err.response !== undefined) ? err.response.data.message : err
          this.$swal({
            type: 'error',
            title: 'Error',
            text: message,
          })
        })
        .finally(res => {
          this.showLoading = false
        })
    },

    actionDocument(action) {
      switch (action) {
        case 'SQ':
        case 'SO':
        case 'PQ':
        case 'PO':
          const document = this.$route.query.document
          this.$router.push({
            path: '/dashboard/documents/form',
            query: {
              document: 0,
              type: action
            }
          })
          setTimeout(() => {
            this.actionName = 'Save'
            this.getBreadcrumb(action, this.form, this.form.status)
            this.$refs.formDocument.changeValue('type', action)
            this.$refs.formDocument.changeValue('parent_id', document)
          }, 300)
          break;
        case 'C':
          this.$refs.formDocument.changeValue('status', 'cancel')
          this.store()
          break;
        case 'sendEmail':
          this.openDialogEmail()
          break;
      }
    },

    deleteDocument(document) {
      const vm = this
      this.$swal({
        title: 'Are you sure?',
        text: 'The data will be deleted',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
      }).then((result) => {
        if (result.value) {
          this.$axios
            .delete(this.url + '/' + document)
            .then((res) => {
              this.$swal({
                type: 'success',
                title: 'Success...',
                text: 'Data Deleted!',
              })
              this.$router.push({
                path: vm.$helper.mappingAction(vm.$route.query.type)
              })
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

    printAction(action) {
      switch (action) {
        case 'preview':
          this.previewDocument()
          break;
        case 'sendEmail':
          this.openDialogEmail()
          break;
      }
    },

    openDialogEmail() {
      this.$refs.dialogSendEmail.openEmailDialog(this.form)
    },

    previewDocument() {
      const vm = this
      this.dialogLoading = true
      this.$axios.get(`/api/documents/print`, {
        params: {
          id: vm.form.id
        },
        responseType: 'arraybuffer'
      })
        .then(response => {
          this.dialogLoading = false
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');

          link.href = url;
          link.setAttribute('download', vm.form.document_number + '.pdf'); // set custom file name
          document.body.appendChild(link);

          link.click(); // force download file without open new tab
        })
        .catch((err) => {
          this.dialogLoading = false
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    getBreadcrumb(type, form, status) {
      const text = (this.$route.query.document !== '0') ? form.document_number : 'Create Document'
      this.breadcrumb = [
        {
          text: 'Dashboard',
          disabled: false,
          to: {
            path: '/dashboard'
          }
        },
        {
          text: this.$helper.mapping(type),
          disabled: false,
          to: {
            path: this.$helper.mappingAction(type)
          }
        },
        {
          text: text,
          disabled: true,
          to: {
            path: '/dashboard/documents/form'
          }
        }
      ]
    },

    mappingType(type) {
      switch (type) {
        case 'SQ':
          return '/dashboard/sales/quote'
      }
    },

    store() {
      const method = (this.$route.query.document === '0') ? 'post' : 'patch'
      const url = (this.$route.query.document === '0') ? this.url : this.url + '/' + this.$route.query.document
      let data = this.$refs.formDocument.returnData(this.$route.query.document)

      this.dialogLoading = true
      this.$axios({method, url, data})
        .then((res) => {
          this.$router.push({
            path: '/dashboard/documents/form',
            query: {
              document: res.data.data.id,
              type: res.data.data.type
            }
          })
          this.$nuxt.$emit('snackbar', res.data.message)
          setTimeout(() => {
            this.getDataFromApi()
          }, 50)
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
        .finally(res => {
          this.dialogLoading = false
        })
    },
  }
}
</script>
