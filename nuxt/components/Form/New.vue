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
        { text: 'Sales Quotations', type: 'SQ', action: 'document' },
        { text: 'Sales Order', type: 'SO', action: 'document' },
        { text: 'Sales Delivery', type: 'SD', action: 'document' },
        { text: 'A/R Invoice', type: 'SI', action: 'transaction' },
        { text: 'Incoming Payment', type: 'SP', action: 'transaction' },
        { text: 'Credit Memo', type: 'SP', action: 'transaction' },
        { text: 'Sales Return', type: 'SR', action: 'transaction' },
      ],

      itemPurchase: [
        { text: 'Expense', type: 'EXPENSE', action: 'document' },
        { text: 'Purchase Order', type: 'PO', action: 'document' },
        { text: 'A/P Invoice', type: 'PI', action: 'transaction' },
        { text: 'Outgoing Payment', type: 'PP', action: 'transaction' },
      ],

      itemOther: [
        { text: 'Bank Deposit', type: 'DEPOSIT', action: 'transaction' },
        { text: 'Transfer', type: 'TRANSFER', action: 'transaction' },
        { text: 'Journal Entry', type: 'JE', action: 'transaction' },
        { text: 'Statement', type: 'STATEMENT', action: 'transaction' },
        {
          text: 'Inventory Qty Adjustment',
          type: 'INVADJ',
          action: 'transaction',
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
