<template>
  <LazyFormDialogFull ref="dialogFull">
    <template #content>
      <v-card elevation="0">
        <v-container fluid>
          <v-row no-gutters>
            <v-col
              cols="12"
              md="2"
              style="background-color: #f5f5f5 !important"
            >
              <v-list style="background-color: #f5f5f5 !important">
                <v-list-item-group v-model="selectedItem" color="primary">
                  <v-list-item
                    v-for="(item, i) in items"
                    :key="i"
                    @click="changeTabValue(item.alias, item.action)"
                  >
                    <!-- <v-list-item-icon>
                      <v-icon v-text="item.icon"></v-icon>
                    </v-list-item-icon> -->

                    <v-list-item-content>
                      <v-list-item-title
                        v-text="item.text"
                        class="font-weight-bold text-subtitle-1"
                      ></v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-item-group>
              </v-list>
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
                <LazySetupForm v-show="!loading" ref="setupForm" />
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-card>
    </template>
    <template #actions>
      <v-btn
        color="green darken-1"
        class="mr-3"
        dark
        small
        @click="$refs.dialogFull.closeDialog()"
      >
        Done
      </v-btn>
    </template>
  </LazyFormDialogFull>
</template>

<script>
export default {
  name: 'OtherSettings',
  data() {
    return {
      tabValue: 'company',
      loading: true,
      loadingButton: false,
      selectedItem: 0,
      form: {},
      showAction: true,
      items: [
        {
          text: 'Company',
          icon: 'mdi-office-building-cog',
          alias: 'company',
          action: true,
        },
        {
          text: 'Sales',
          icon: 'mdi-finance',
          alias: 'sales',
          action: true,
        },
        {
          text: 'Expenses',
          icon: 'mdi-finance',
          alias: 'expenses',
          action: true,
        },
        // {
        //   text: 'Payment',
        //   icon: 'mdi-finance',
        //   alias: 'payment',
        //   action: true,
        // },
        {
          text: 'Advanced',
          icon: 'mdi-finance',
          alias: 'advanced',
          action: true,
        },
      ],
    }
  },

  mounted() {
    this.selectedItem = 0
    this.changeTabValue('company', true)
  },

  methods: {
    openDialog(data) {
      this.selectedItem = 0
      this.$refs.dialogFull.openDialog(data)
      this.changeTabValue('company', true)
    },

    changeTabValue(page, action) {
      this.$axios
        .get(`/api/settings`, {
          params: {
            page,
          },
        })
        .then((res) => {
          this.loading = true
          this.tabValue = page
          this.form = res.data.data.form
          // this.$router.push({
          //   path: '/dashboard/settings/setup',
          //   query: {
          //     page,
          //   },
          // })
          setTimeout(() => {
            this.loading = false
            this.showAction = action === undefined ? true : action
            if (this.$refs.setupForm) {
              this.$refs.setupForm.changeTab(this.form, res.data.data.url, page)
            }
          }, 800)
        })
    },

    save() {
      const form = this.$refs.setupForm.getForm()
      let options = {}
      let url = '/api/settings'
      if (this.tabValue === 'company') {
        options = {
          headers: {
            'Content-Type':
              'Multipart/form-data; charset=utf-8; boundary=' +
              Math.random().toString().substr(2),
          },
        }
      } else if (this.tabValue === 'account_mapping') {
        url = '/api/financial/account-mapping'
      }

      this.loadingButton = true
      this.$axios
        .post(url, form, options)
        .then((res) => {
          this.loadingButton = false

          if (this.tabValue === 'company') {
            this.$nuxt.$emit('getLogo')
            this.$nuxt.$emit('getCompany')
          }

          this.changeTabValue(this.tabValue)
        })
        .catch((err) => {
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
