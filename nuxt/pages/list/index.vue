<template>
  <v-container>
    <v-row>
      <v-col cols="12">
        <span class="text-h4">List</span>
      </v-col>
      <v-col cols="12" md="4">
        <LazyFormListItem
          title="Chart of accounts"
          description="Displays your accounts. Balance sheet accounts track your assets and liabilities, and income and expense accounts categorize your transactions. From here, you can add or edit accounts."
          url="/financial/account"
        ></LazyFormListItem>
      </v-col>

      <v-col cols="12" md="4">
        <LazyFormListItem
          title="Product and services"
          description="Displays the products and services you sell. From here, you can edit information about a product or service, such as its description, or the rate you charge."
          url="/app/item/list"
        ></LazyFormListItem>
      </v-col>

      <v-col>
        <LazyFormListItem
          title="Permissions"
          description="Display list of permissions"
          url="/list/permissions"
        ></LazyFormListItem>
      </v-col>

      <v-col cols="12" md="4">
        <LazyFormListItem
          title="Roles"
          description="Display list of roles"
          url="/list/roles"
        ></LazyFormListItem>
      </v-col>

      <v-col cols="12" md="4">
        <LazyFormListItem
          title="Tax"
          description="Display list of sales tax"
          url="/list/tax"
        ></LazyFormListItem>
      </v-col>

      <v-col cols="12" md="4">
        <LazyFormListItem
          title="Users"
          description="Display list of users"
          url="/list/users"
        ></LazyFormListItem>
      </v-col>

      <v-col cols="12" md="4">
        <LazyFormListItem
          title="Currency"
          url="/list/currency"
        ></LazyFormListItem>
      </v-col>

      <v-col cols="12" md="4">
        <LazyFormListItem
          title="Payment Term"
          description="Displays the list of terms that determine the due dates for payments from customers, or payments to vendors. Terms can also specify discounts for early payment. From here, you can add or edit terms."
          url="/list/paymentterm"
        ></LazyFormListItem>
      </v-col>

      <v-col cols="12" md="4">
        <LazyFormListItem
          title="Payment Method"
          description="Displays Cash, Check, and any other ways you categorize payments you receive from customers. That way, you can print deposit slips when you deposit the payments you have received."
          url="/list/paymentmethod"
        ></LazyFormListItem>
      </v-col>

      <v-col cols="12" md="4">
        <LazyFormListItem
          title="attachment"
          description="Displays the list of all attachments uploaded. From here you can add, edit, download, and export your attachments. You can also see all transactions linked to a particular attachment."
          url="/list/attachment"
        ></LazyFormListItem>
      </v-col>

      <v-col cols="12" md="4">
        <LazyFormListItem
          title="Recurring"
          description="Displays a list of transactions that have been saved for reuse. From here, you can schedule transactions to occur either automatically or with reminders. You can also save unscheduled transactions to use at any time."
          url="/list/recurring"
        ></LazyFormListItem>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'SalesDelivery',

  head() {
    return {
      title: 'All Lists',
    }
  },
}
</script>
