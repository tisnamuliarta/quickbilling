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
        { text: 'Sales Quotations', type: 'SQ', route: '/app/sales/quote/form' },
        { text: 'Sales Order', type: 'SO', route: '/app/sales/order/form' },
        { text: 'Sales Delivery', type: 'SD', route: '/app/sales/delivery/form' },
        { text: 'A/R Invoice', type: 'SI', route: '/app/sales/invoice/form' },
        { text: 'Incoming Payment', type: 'SP', route: '/app/sales/payment/form' },
        { text: 'A/R Credit Memo', type: 'ARCM', route: '/app/sales/creditmemo/form' },
        { text: 'Sales Return', type: 'SR', route: '/app/sales/return/form' },
      ],

      itemPurchase: [
        { text: 'Expense', type: 'EXPENSE', route: '/app/expense/form' },
        { text: 'Purchase Order', type: 'PO', route: '/app/purchase/order/form' },
        { text: 'A/P Invoice', type: 'PI', route: '/app/purchase/invoice/form' },
        { text: 'Outgoing Payment', type: 'PP', route: '/app/purchase/payment/form' },
        { text: 'A/P Credit Memo', type: 'APCM', route: '/app/purchase/creditmemo/form' },
        { text: 'Goods Return', type: 'GR', route: '/app/purchase/return/form' },
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
