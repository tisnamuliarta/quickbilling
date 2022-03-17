<template>
  <v-container fluid>
    <v-row no-gutters>
      <v-col cols="12">
        <v-card>
          <v-toolbar flat color='grey lighten-2' dense style='height: 32px' class='toolbar-content'>
            <v-divider class='mx-2' inset vertical></v-divider>
            <!--<breadcrumbs/>-->

            <v-breadcrumbs
              :items='breadcrumb'
              divider='/'
              class='hidden-xs-only'
            ></v-breadcrumbs>

            <v-spacer class='hidden-xs-only'></v-spacer>
            <v-btn icon @click="getDataFromApi">
              <v-icon>mdi-refresh</v-icon>
            </v-btn>
          </v-toolbar>

          <DocumentFormDocument ref="formDocument"></DocumentFormDocument>

          <v-card-actions>
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

            <v-spacer></v-spacer>

            <v-btn
              color="green darken-1"
              dark
              small
            >
              Save
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>

    <LazySpinnerLoading
      v-if='dialogLoading'
      ref='spinnerLoadingImport'
    ></LazySpinnerLoading>
  </v-container>
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
        .get(`/api/documents/` + this.$route.query.id, {
          params: {
            type,
          },
        })
        .then((res) => {
          this.form = Object.assign({}, res.data.data.form)
          this.defaultItem = Object.assign({}, res.data.data.form)
          this.getBreadcrumb(type)
          this.$refs.formDocument.setData(this.defaultItem)
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
  }
}
</script>
