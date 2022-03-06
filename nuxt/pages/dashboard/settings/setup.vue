<template>
  <v-layout>
    <v-flex sm12>
      <v-card>
        <v-toolbar flat dense color="primary" dark>
          <v-toolbar-title>Other Settings</v-toolbar-title>
        </v-toolbar>
        <v-card-text>
          <v-container>
            <v-row no-gutters>
              <v-col cols="12" md="2">
                <v-navigation-drawer permanent>
                  <v-list nav dense>
                    <v-list-item-group v-model="selectedItem" color="primary">
                      <v-list-item
                        v-for="(item, i) in items"
                        :key="i"
                        @click="changeTabValue(item.alias)"
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
                  <SetupForm v-show="!loading" ref="setupForm" />
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn color="blue darken-1" class="mr-3" dark small>
            Save
            <v-icon small dark right> mdi-content-save </v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: 'MasterRole',
  layout: 'dashboard',
  data() {
    return {
      tabValue: 'company',
      loading: true,
      selectedItem: 0,
      items: [
        { text: 'Company', icon: 'mdi-office-building-cog', alias: 'company' },
        { text: 'Finance', icon: 'mdi-finance', alias: 'finance' },
        { text: 'E-Mail', icon: 'mdi-email', alias: 'email' },
        { text: 'PDF', icon: 'mdi-file-pdf-box', alias: 'pdf' },
        { text: 'Tags', icon: 'mdi-tag', alias: 'tags' },
        { text: 'Product Units', icon: 'mdi-align-vertical-distribute', alias: 'units' },
        { text: 'Product Category', icon: 'mdi-shape', alias: 'product_category' },
        { text: 'Payment Method', icon: 'mdi-bank-transfer', alias: 'payment' },
        { text: 'Taxes', icon: 'mdi-content-cut', alias: 'taxes' },
        { text: 'Terms', icon: 'mdi-file-document-outline', alias: 'term' },
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
    changeTabValue(alias) {
      this.loading = true
      this.tabValue = alias
      this.$router.push({
        path: '/dashboard/settings/setup',
        query: {
          page: alias,
        },
      })
      setTimeout(() => {
        this.loading = false
        this.$refs.setupForm.changeTab(alias)
      }, 300)
    },
  },
}
</script>
