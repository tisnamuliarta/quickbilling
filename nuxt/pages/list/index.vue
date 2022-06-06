<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <span class="text-h4">List</span>
      </v-col>

      <v-col cols="12" md="10">
        <v-expansion-panels v-model="panel" multiple ref="tableDocument">
          <v-expansion-panel v-for="(item, i) in items" :key="i">
            <v-expansion-panel-header>{{
              item.header
            }}</v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-list link dense>
                <v-list-item
                  v-for="(value, index) in item.items"
                  :key="index"
                  :to="item.route"
                  two-line
                  @click="$router.push({ path: value.route })"
                  link
                  dense
                  class="pt-0 pb-0"
                >
                  <v-list-item-content>
                    <v-list-item-title
                      class="font-weight-bold"
                      v-text="value.name"
                    ></v-list-item-title>

                    <v-list-item-subtitle
                      v-text="value.desc"
                    ></v-list-item-subtitle>

                    <v-divider></v-divider>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'AllList',

  data() {
    return {
      panel: [0, 1, 2, 3, 4],
      items: [
        {
          header: 'Accounting',
          items: [
            {
              name: 'Chart of accounts',
              route: '/app/accounting/account',
              desc: 'Displays your accounts. Balance sheet accounts track your assets and liabilities, and income and expense accounts categorize your transactions. From here, you can add or edit accounts.',
            },
            {
              name: 'Taxes',
              route: 'Display list of sales tax',
              desc: 'Display list of sales tax',
            },
          ],
        },

        {
          header: 'General',
          items: [
            {
              name: 'Users',
              route: '/list/users',
              desc: 'Display list of users',
            },
            {
              name: 'Permissions',
              route: '/list/permissions',
              desc: 'Display list of permissions',
            },
            {
              name: 'Roles',
              route: '/list/roles',
              desc: 'Display list of roles',
            },
          ],
        },

        {
          header: 'Transaction',
          items: [
            {
              name: 'Currency',
              route: '/list/currency',
              desc: '',
            },
            {
              name: 'Product and services',
              route: '/app/item/list',
              desc: 'Displays the products and services you sell. From here, you can edit information about a product or service, such as its description, or the rate you charge.',
            },
            {
              name: 'Payment Term',
              route: '/list/paymentterm',
              desc: 'Displays the list of terms that determine the due dates for payments from customers, or payments to vendors. Terms can also specify discounts for early payment. From here, you can add or edit terms.',
            },
            {
              name: 'Payment Method',
              route: '/list/paymentmethod',
              desc: 'Displays Cash, Check, and any other ways you categorize payments you receive from customers. That way, you can print deposit slips when you deposit the payments you have received.',
            },
            {
              name: 'Recurring',
              route: '/list/recurring',
              desc: 'Displays a list of transactions that have been saved for reuse. From here, you can schedule transactions to occur either automatically or with reminders. You can also save unscheduled transactions to use at any time.',
            },
          ],
        },

        {
          header: 'Other',
          items: [
            {
              name: 'Attachment',
              route: '/list/attachment',
              desc: 'Displays the list of all attachments uploaded. From here you can add, edit, download, and export your attachments. You can also see all transactions linked to a particular attachment.',
            },
          ],
        },
      ],
    }
  },

  head() {
    return {
      title: 'All Lists',
    }
  },

  activated() {
    this.$nuxt.$emit('extensionSetting', {
      show: false,
      showBtn: false,
    })
  },
}
</script>
