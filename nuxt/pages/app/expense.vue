<template>
  <div>
    <DocumentTableDocument
      ref="tableDocument"
      type-document="EXPENSE"
      form-url="/app/expense/form"
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
    <NuxtChild keep-alive :keep-alive-props="{include: ['form']}"></NuxtChild>
  </div>
</template>

<script>
export default {
  name: 'Expense',
  layout: 'dashboard',

  head() {
    return {
      title: 'Expense',
    }
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

