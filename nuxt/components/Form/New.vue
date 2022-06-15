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
        {
          text: this.$t('Sales Quotations'),
          type: 'SQ',
          route: '/app/form/sales/quote',
        },
        {
          text: this.$t('Sales Order'),
          type: 'SO',
          route: '/app/form/sales/order',
        },
        {
          text: this.$t('Sales Delivery'),
          type: 'SD',
          route: '/app/form/sales/delivery',
        },
        {
          text: this.$t('A/R Invoice'),
          type: 'SI',
          route: '/app/form/sales/invoice',
        },
        {
          text: this.$t('Incoming Payment'),
          type: 'SP',
          route: '/app/form/sales/payment',
        },
        {
          text: this.$t('A/R Credit Memo'),
          type: 'ARCM',
          route: '/app/form/sales/creditmemo',
        },
        {
          text: this.$t('Sales Return'),
          type: 'SR',
          route: '/app/form/sales/return',
        },
      ],

      itemPurchase: [
        {
          text: this.$t('Expenses'),
          type: 'EXPENSE',
          route: '/app/form/purchase/expense',
        },
        {
          text: this.$t('Purchase Quotations'),
          type: 'PQ',
          route: '/app/form/purchase/quote',
        },
        {
          text: this.$t('Purchase Order'),
          type: 'PO',
          route: '/app/form/purchase/order',
        },
        {
          text: this.$t('A/P Invoice'),
          type: 'PI',
          route: '/app/form/purchase/invoice',
        },
        {
          text: this.$t('Outgoing Payment'),
          type: 'PP',
          route: '/app/form/purchase/payment',
        },
        {
          text: this.$t('A/P Credit Memo'),
          type: 'APCM',
          route: '/app/form/purchase/creditmemo',
        },
        {
          text: this.$t('Goods Return'),
          type: 'GR',
          route: '/app/form/purchase/return',
        },
      ],

      itemEmployee: [
        {
          text: this.$t('Single time activity'),
          type: '',
          route: '/app/form/employee/timeactivity',
        },
        {
          text: this.$t('Weekly timesheet'),
          type: '',
          route: '/app/form/employee/timesheet',
        },
      ],

      itemOther: [
        {
          text: this.$t('Tasks'),
          type: 'TASK',
          route: '/app/form/project/task',
        },
        {
          text: this.$t('Bank Deposit'),
          type: 'DEPOSIT',
          route: '/app/form/accounting/deposit',
        },
        {
          text: this.$t('Transfer'),
          type: 'TRANSFER',
          route: '/app/form/accounting/transfer',
        },
        {
          text: this.$t('Journal Entry'),
          type: 'JE',
          route: '/app/form/accounting/journal',
        },
        {
          text: this.$t('Statement'),
          type: 'STATEMENT',
          route: '/app/form/accounting/statements',
        },
        {
          text: this.$t('Inventory Qty Adjustment'),
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
