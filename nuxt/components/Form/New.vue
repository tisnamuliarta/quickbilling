<template>
  <v-container fluid>
    <v-row no-gutters>
      <v-col cols="12" md="3">
        <LazyFormListAction
          title="SALES"
          :items="itemSales"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>

      <v-col cols="12" md="3">
        <LazyFormListAction
          title="PURCHASING"
          :items="itemPurchase"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>

      <v-col cols="12" md="3">
        <LazyFormListAction
          title="EMPLOYEE"
          :items="itemEmployee"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>

      <v-col cols="12" md="3">
        <LazyFormListAction
          title="OTHER"
          :items="itemOther"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'New',

  data() {
    return {
      dialog: false,
      itemSales: [
        { text: 'Sales Quotations', type: 'SQ', route: '/app/form/sales/quote' },
        { text: 'Sales Order', type: 'SO', route: '/app/form/sales/order' },
        { text: 'Sales Delivery', type: 'SD', route: '/app/form/sales/delivery' },
        { text: 'A/R Invoice', type: 'SI', route: '/app/form/sales/invoice' },
        { text: 'Incoming Payment', type: 'SP', route: '/app/form/sales/payment' },
        { text: 'A/R Credit Memo', type: 'ARCM', route: '/app/form/sales/creditmemo' },
        { text: 'Sales Return', type: 'SR', route: '/app/form/sales/return' },
      ],

      itemPurchase: [
        { text: 'Purchase Quotations', type: 'PQ', route: '/app/form/purchase/quote' },
        { text: 'Purchase Order', type: 'PO', route: '/app/form/purchase/order' },
        { text: 'A/P Invoice', type: 'PI', route: '/app/form/purchase/invoice' },
        { text: 'Outgoing Payment', type: 'PP', route: '/app/form/purchase/payment' },
        { text: 'A/P Credit Memo', type: 'APCM', route: '/app/form/purchase/creditmemo' },
        { text: 'Goods Return', type: 'GR', route: '/app/form/purchase/return' },
      ],

      itemEmployee: [
        { text: 'Single time activity', type: '', route: '/app/form/employee/timeactivity' },
        { text: 'Weekly timesheet', type: '', route: '/app/form/employee/timesheet' },
      ],

      itemOther: [
        { text: 'Task', type: 'TASK', route: '/app/form/project/task' },
        { text: 'Bank Deposit', type: 'DEPOSIT', route: '/app/form/accounting/deposit' },
        { text: 'Transfer', type: 'TRANSFER', route: '/app/form/accounting/transfer' },
        { text: 'Journal Entry', type: 'JE', route: '/app/form/accounting/journal' },
        { text: 'Statement', type: 'STATEMENT', route: '/app/form/accounting/statements' },
        {
          text: 'Inventory Qty Adjustment',
          type: 'INVADJ',
          route: '/app/invtoryqtyadjustment',
        },
      ],
    }
  },

  methods: {
    openDialog() {
      this.dialog = true
    },

    openAction(data) {
      this.$emit('openAction', {
        item: data.item,
      })
      this.dialog = false
    },
  },
}
</script>
