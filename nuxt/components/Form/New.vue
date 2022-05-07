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
        { text: 'Sales Quotations', type: 'SQ', route: '/app/quotation' },
        { text: 'Sales Order', type: 'SO', route: '/app/salesorder' },
        { text: 'Sales Delivery', type: 'SD', route: '/app/salesdelivery' },
        { text: 'A/R Invoice', type: 'SI', route: '/app/salesinvoice' },
        { text: 'Incoming Payment', type: 'SP', route: '/app/salespayment' },
        { text: 'A/R Credit Memo', type: 'ARCM', route: '/app/salescreditmemo' },
        { text: 'Sales Return', type: 'SR', route: '/app/salesreturn' },
      ],

      itemPurchase: [
        { text: 'Expense', type: 'EXPENSE', route: '/app/expenses' },
        { text: 'Purchase Order', type: 'PO', route: '/app/purchaseorder' },
        { text: 'A/P Invoice', type: 'PI', route: '/app/purchaseinvoice' },
        { text: 'Outgoing Payment', type: 'PP', route: '/app/outgoingpaymment' },
        { text: 'A/P Credit Memo', type: 'APCM', route: '/app/purchasecreditmemo' },
        { text: 'Goods Return', type: 'GR', route: '/app/goodsreturn' },
      ],

      itemOther: [
        { text: 'Bank Deposit', type: 'DEPOSIT', route: '/app/deposit' },
        { text: 'Transfer', type: 'TRANSFER', route: '/app/transfer' },
        { text: 'Journal Entry', type: 'JE', route: '/app/journal' },
        { text: 'Statement', type: 'STATEMENT', route: '/app/statements' },
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
