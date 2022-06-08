<template>
  <div>
    <DocumentTableDocument
      ref="tableDocument"
      type-document="P"
      form-url="/app/purchasereturn/form"
      table-title="Purchase Transactions"
      :item-multiple="itemMultiple"
      :header-table="[
        {text: 'Number', value: 'document_number', cellClass: 'disable-wrap'},
        {text: 'Customer', value: 'contact_name', cellClass: 'disable-wrap'},
        {text: 'Date', value: 'issued_at', cellClass: 'disable-wrap', sortable: false, filterable: false},
        {text: 'Due Date', value: 'due_at', cellClass: 'disable-wrap', sortable: false, filterable: false},
        {text: 'Status', value: 'status', align: 'left', cellClass: 'disable-wrap', sortable: false, filterable: false},
        {
          text: 'Balance Due',
          value: 'balance_due',
          align: 'right',
          cellClass: 'disable-wrap',
          sortable: false,
          filterable: false
        },
        {text: 'Total', value: 'amount', align: 'right', cellClass: 'disable-wrap', sortable: false, filterable: false},
        {
          text: 'Actions',
          value: 'actions',
          align: 'center',
          cellClass: 'disable-wrap',
          sortable: false,
          filterable: false
        },
      ]"
    ></DocumentTableDocument>
  </div>
</template>

<script>
export default {
  name: 'AllPurchase',

  data() {
    return {
      itemMultiple: [
        { text: 'Expense', type: 'EXPENSE', route: '/app/expense' },
        { text: 'Purchase Order', type: 'PO', route: '/app/form/purchase/order' },
        { text: 'A/P Invoice', type: 'PI', route: '/app/form/purchase/invoice' },
        { text: 'Outgoing Payment', type: 'PP', route: '/app/form/purchase/payment' },
        { text: 'A/P Credit Memo', type: 'APCM', route: '/app/form/purchase/creditmemo' },
        { text: 'Goods Return', type: 'GR', route: '/app/form/purchase/return' },
      ],
    }
  },

  head() {
    return {
      title: 'All Sales',
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
    }
  }
}
</script>



