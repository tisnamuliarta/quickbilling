<template>
  <v-layout>
    <v-flex sm12>
      <v-card>
        <v-container fluid>
          <v-row no-gutters>
            <v-col cols="12" md="2">
              <v-navigation-drawer permanent>
                <v-list nav dense>
                  <v-list-item-group v-model="selectedItem" color="primary">
                    <v-list-item
                      v-for="(item, i) in items"
                      :key="i"
                      @click="changeTabValue(item.alias, item.action)"
                    >
                      <v-list-item-icon>
                        <v-icon v-text="item.icon"></v-icon>
                      </v-list-item-icon>

                      <v-list-item-content>
                        <v-list-item-title
                          v-text="item.text"
                        ></v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                  </v-list-item-group>
                </v-list>
              </v-navigation-drawer>
            </v-col>
            <v-col cols="12" md="10">
              <v-card flat>
                <v-skeleton-loader
                  v-if="loading"
                  type="article, actions"
                  class="mx-auto"
                  :loading="loading"
                >
                </v-skeleton-loader>
                <SetupForm v-show="!loading" ref="setupForm"/>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
        <v-divider v-if="showAction"></v-divider>
        <v-card-actions>
          <v-spacer />
          <v-btn
            v-if="showAction"
            color="green darken-1"
            class="mr-3"
            dark
            small
            :loading="loadingButton"
            @click="save"
          >
            Save
            <v-icon small dark right> mdi-content-save</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: 'OtherSettings',
  layout: 'dashboard',
  data() {
    return {
      tabValue: 'company',
      loading: true,
      loadingButton: false,
      selectedItem: 0,
      form: {},
      showAction: true,
      items: [
        {text: 'Company', icon: 'mdi-office-building-cog', alias: 'company', action: true},
        {text: 'Finance', icon: 'mdi-finance', alias: 'finance', action: true},
        {text: 'Account Mapping', icon: 'mdi-map', alias: 'account_mapping', action: true},
        {text: 'E-Mail', icon: 'mdi-email', alias: 'email', action: true},
        {text: 'PDF', icon: 'mdi-file-pdf-box', alias: 'pdf', action: true},
        {text: 'Tags', icon: 'mdi-tag', alias: 'tags', action: false},
        {text: 'Item Units', icon: 'mdi-align-vertical-distribute', alias: 'units', action: false},
        {text: 'Item Category', icon: 'mdi-shape', alias: 'product_category', action: false},
        {text: 'Permissions', icon: 'mdi-boom-gate', alias: 'permissions', action: false},
        {text: 'Roles', icon: 'mdi-police-badge', alias: 'roles', action: false},
        {text: 'Users', icon: 'mdi-account', alias: 'users', action: false},
        {text: 'Payment Method', icon: 'mdi-bank-transfer', alias: 'payment', action: false},
        {text: 'VAT', icon: 'mdi-content-cut', alias: 'taxes', action: false},
        {text: 'Terms', icon: 'mdi-file-document-outline', alias: 'term', action: false},
        {text: 'Currency', icon: 'mdi-currency-usd', alias: 'currency', action: false},
      ],
    }
  },

  head() {
    return {
      title: 'Application Setup',
    }
  },

  created() {
    this.changeTabValue(this.tabValue)
  },

  methods: {
    changeTabValue(page, action) {
      this.$axios.get(`/api/settings`, {
        params: {
          page
        }
      })
        .then(res => {
          this.loading = true
          this.tabValue = page
          this.form = res.data.data.form
          this.$router.push({
            path: '/dashboard/settings/setup',
            query: {
              page,
            },
          })
          setTimeout(() => {
            this.loading = false
            this.showAction = (action === undefined) ? true : action
            this.$refs.setupForm.changeTab(this.form, res.data.data.url)
          }, 300)
        })
    },

    save() {
      const form = this.$refs.setupForm.getForm()
      let options = {}
      let url = '/api/settings'
      if (this.tabValue === 'company') {
        options = {
          headers: {
            'Content-Type': "Multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
          }
        }
      } else if (this.tabValue === 'account_mapping') {
        url = '/api/financial/account-mapping'
      }

      this.loadingButton = true
      this.$axios.post(url, form, options)
        .then(res => {
          this.loadingButton = false

          if (this.tabValue === 'company') {
            this.$nuxt.$emit('getLogo')
            this.$nuxt.$emit('getCompany')
          }

          this.changeTabValue(this.tabValue)
        })
        .catch(err => {
          this.loadingButton = false
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },
  },
}
</script>
