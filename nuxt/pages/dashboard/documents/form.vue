<template>
  <div>
    <DocumentFormWindow
      ref="formWindow"
      :breadcrumb="breadcrumb"
      @getDataFromApi="getDataFromApi"
    >
      <template #content>
        <LazyDocumentFormDocument ref="formDocument"></LazyDocumentFormDocument>
      </template>

      <template #action>
        <v-menu
          offset-y
        >
          <template v-slot:activator="{ attrs, on }">
            <v-btn
              class="white--text"
              color="primary"
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

          <v-list>
            <v-list-item
              v-for="item in items"
              :key="item"
              link
            >
              <v-list-item-title v-text="item"></v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>

        <v-menu
          offset-y
        >
          <template v-slot:activator="{ attrs, on }">
            <v-btn
              class="white--text ml-5"
              color="primary"
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
              :key="item"
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
      items: [],
      url: '/api/documents',
      dialogLoading: false,
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
      this.dialogLoading = true
      const type = this.$route.query.type
      this.$axios
        .get(this.url + '/' + this.$route.query.id, {
          params: {
            type,
          },
        })
        .then((res) => {
          this.form = Object.assign({}, res.data.data.form)
          this.defaultItem = Object.assign({}, res.data.data.form)
          this.getBreadcrumb(type)
          setTimeout(() => {
            this.$refs.formDocument.setData(this.defaultItem)
          }, 100)
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

    getBreadcrumb(type) {
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
          text: 'Create Document',
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
          this.getDataFromApi()
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
