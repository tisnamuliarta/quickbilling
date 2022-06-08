<template>
  <div>
    <v-expansion-panels v-model="panel" multiple ref="tableDocument">
      <v-expansion-panel v-for="(item, i) in items" :key="i">
        <v-expansion-panel-header>{{ item.header }}</v-expansion-panel-header>
        <v-expansion-panel-content>
          <v-list link dense>
            <v-list-item-group>
              <v-row dense>
                <template v-for="(value, index) in item.items">
                  <v-col :key="index" cols="12" md="6" class="pa-1">
                    <v-list-item dense class="pt-0 pb-0">
                      <v-list-item-title
                        v-text="value.name"
                      ></v-list-item-title>
                      <v-list-item-action>
                        <v-list-item-action-text
                          v-text="item.action"
                        ></v-list-item-action-text>
                        <v-icon color="yellow darken-3">
                          mdi-star-outline
                        </v-icon>
                      </v-list-item-action>
                    </v-list-item>
                    <v-divider></v-divider>
                  </v-col>
                </template>
              </v-row>
            </v-list-item-group>
          </v-list>
        </v-expansion-panel-content>
      </v-expansion-panel>
    </v-expansion-panels>
  </div>
</template>

<script>
export default {
  name: 'AllSales',

  data() {
    return {
      panel: [0, 1, 2, 3, 4],
      items: [
        {
          header: 'Business Overview',
          items: [
            { name: 'Audit Log', route: '' },
            { name: 'Ballance sheet', route: '' },
            { name: 'Profilt and loss', route: '' },
            { name: 'Statement of cash flow', route: '' },
          ],
        },
        {
          header: 'Who owe you',
          items: [
            { name: 'Account receivable aging detail', route: '' },
            { name: 'Account receivable aging summary', route: '' },
            { name: 'Open invoice', route: '' },
          ],
        },
        {
          header: 'Sales and customer',
          items: [
            { name: 'Sales by customer detail', route: '' },
            { name: 'Sales by customer summary', route: '' },
          ],
        },
        {
          header: 'Expense and vendor',
          items: [
            { name: 'Purchase by vendor detail', route: '' },
            { name: 'Purchase by vendor summary', route: '' },
          ],
        },
        {
          header: 'Payroll',
          items: [
            { name: 'payroll sumarry', route: '' },
            { name: 'Payroll details', route: '' },
          ],
        },
      ],
    }
  },

  head() {
    return {
      title: 'Reports',
    }
  },

  activated() {
    this.$nuxt.$emit('extensionSetting', {
      show: false,
      showBtn: false,
    })
  },

  created() {
    this.$nuxt.$on('getDataFromApi', ($event) => this.getDataFromApi($event))
  },

  methods: {
    getDataFromApi() {
      if (this.$refs.tableDocument) {
        this.$refs.tableDocument.getDataFromApi()
      }
    },
  },
}
</script>
