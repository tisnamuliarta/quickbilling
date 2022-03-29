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
              v-for="item in items"
              :key="item.title"
              link
            >
              <v-list-item-title v-text="item"></v-list-item-title>
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
          {{ $route.query.status }}
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

  mounted() {
    this.getDataFromApi()
  },

  methods: {
    getDataFromApi() {
      // this.dialogLoading = true
      this.showLoading = true
      let status = this.$route.query.status
      const type = this.$route.query.type
      this.$axios
        .get(this.url + '/' + this.$route.query.id, {
          params: {
            type,
          },
        })
        .then((res) => {
          const form = (status === 'update') ? res.data.data.rows : res.data.data.form
          this.form = Object.assign({}, form)
          this.defaultItem = Object.assign({}, form)
          this.getBreadcrumb(type, form, status)

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

    printAction(action) {
      const vm = this
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
      this.$refs.dialogSendEmail.openEmailDialog()
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
          text: 'Documents',
          disabled: false,
          to: {
            path: this.mappingType(type)
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
      const method = (this.$route.query.status === 'save') ? 'post' : 'patch'
      const url = (this.$route.query.status === 'save') ? this.url : this.url + '/' + this.$route.query.id
      let data = this.$refs.formDocument.returnData()

      this.dialogLoading = true
      this.$axios({method, url, data})
        .then((res) => {
          this.$router.push({
            path: '/dashboard/documents/form',
            query: {
              id: res.data.data.id,
              status: res.data.data.status,
              type: res.data.data.type
            }
          })
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
