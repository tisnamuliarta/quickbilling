<template>
  <div>
    <DocumentFormWindow
      ref="formWindow"
      :breadcrumb="breadcrumb"
      @getDataFromApi="getDataFromApi"
    >
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
              dark
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
              dark
              v-bind="attrs"
              small
              v-on="on"
            >
              Action
              <v-icon
                right
                dark
              >
                mdi-content-copy
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
          dark
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

    <LazyDocumentDialogSendEmail ref="dialogSendEmail"></LazyDocumentDialogSendEmail>

    <LazySpinnerLoading
      v-if='dialogLoading'
      ref='spinnerLoadingImport'
    ></LazySpinnerLoading>
  </div>
</template>

<script>
export default {
  name: 'FormDocument',

  layout: 'dashboard',

  data() {
    return {
      breadcrumb: [],
      form: {},
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
    getDataFromApi() {
      // this.dialogLoading = true
      this.showLoading = true
      const type = this.$route.query.type
      this.$axios
        .get(this.url + '/' + this.$route.query.document, {
          params: {
            type,
          },
        })
        .then((res) => {
          let form = ''
          if (res.data.data.count > 0) {
            form = res.data.data.rows
            this.itemAction = this.$helper.itemAction(form.type)
            this.actionName = 'Update'
          } else {
            form = res.data.data.form
            this.actionName = 'Save'
          }
          this.form = Object.assign({}, form)
          this.defaultItem = Object.assign({}, form)
          this.getBreadcrumb(type, form, form.status)

          setTimeout(() => {
            this.$refs.formDocument.setData(this.form)
          }, 30)
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
        .finally(res => {
          this.showLoading = false
        })
    },

    actionDocument(action) {
      switch (action) {
        case 'preview':
          this.previewDocument()
          break;
        case 'sendEmail':
          this.openDialogEmail()
          break;
      }
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
      const text = (status === 'update') ? form.document_number : 'Create Document'
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
      let data = this.$refs.formDocument.returnData()

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
