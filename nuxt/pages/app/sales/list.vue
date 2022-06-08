<template>
  <div>
    <DocumentTableDocument
      ref="tableDocument"
      type-document="S"
      table-title="Sales Transactions"
      form-url="/app/purchasereturn/form"
      :item-multiple="itemMultiple"
      :header-table="[
        {text: 'Number', value: 'document_number', cellClass: 'disable-wrap'},
        {text: 'Customer', value: 'contact_name', cellClass: 'disable-wrap'},
        {text: 'Type', value: 'type', cellClass: 'disable-wrap'},
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
  name: 'AllSales',

  data() {
    return {
      itemMultiple: [
        { text: 'Sales Quotations', type: 'SQ', route: '/app/form/sales/quote' },
        { text: 'Sales Order', type: 'SO', route: '/app/form/sales/order' },
        { text: 'Sales Delivery', type: 'SD', route: '/app/form/sales/delivery' },
        { text: 'A/R Invoice', type: 'SI', route: '/app/form/sales/invoice' },
        { text: 'Incoming Payment', type: 'SP', route: '/app/form/sales/payment' },
        { text: 'A/R Credit Memo', type: 'ARCM', route: '/app/form/sales/creditmemo' },
        { text: 'Sales Return', type: 'SR', route: '/app/form/sales/return' },
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



