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
        { text: 'Sales Quotations', type: 'SQ', route: '/app/sales/quote' },
        { text: 'Sales Order', type: 'SO', route: '/app/sales/order' },
        { text: 'Sales Delivery', type: 'SD', route: '/app/sales/delivery' },
        { text: 'A/R Invoice', type: 'SI', route: '/app/sales/invoice' },
        { text: 'Incoming Payment', type: 'SP', route: '/app/sales/payment' },
        { text: 'A/R Credit Memo', type: 'ARCM', route: '/app/sales/creditmemo' },
        { text: 'Sales Return', type: 'SR', route: '/app/sales/return' },
      ],

      itemPurchase: [
        { text: 'Expense', type: 'EXPENSE', route: '/app/expense' },
        { text: 'Purchase Order', type: 'PO', route: '/app/purchase/order' },
        { text: 'A/P Invoice', type: 'PI', route: '/app/purchase/invoice' },
        { text: 'Outgoing Payment', type: 'PP', route: '/app/purchase/payment' },
        { text: 'A/P Credit Memo', type: 'APCM', route: '/app/purchase/creditmemo' },
        { text: 'Goods Return', type: 'GR', route: '/app/purchase/return' },
      ],

      itemOther: [
        { text: 'Bank Deposit', type: 'DEPOSIT', route: '/app/accounting/deposit' },
        { text: 'Transfer', type: 'TRANSFER', route: '/app/accounting/transfer' },
        { text: 'Journal Entry', type: 'JE', route: '/app/accounting/journal' },
        { text: 'Statement', type: 'STATEMENT', route: '/app/accounting/statements' },
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
