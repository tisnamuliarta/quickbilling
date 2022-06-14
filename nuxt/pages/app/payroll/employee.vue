<template>
  <div>
    <PayrollTableEmployee
      ref="tableDocument"
      type-document="EMPLOYEE"
      table-title="Employees"
      form-url="/app/purchasereturn/form"
      :header-table="[
        {text: 'Name', value: 'name', cellClass: 'disable-wrap'},
        {text: 'Pay Rate', value: 'contact_name', cellClass: 'disable-wrap'},
        {text: 'Pay Method', value: 'type', cellClass: 'disable-wrap'},
        {text: 'Status', value: 'issued_at', cellClass: 'disable-wrap', sortable: false, filterable: false},
        {text: 'Phone Number', value: 'due_at', cellClass: 'disable-wrap', sortable: false, filterable: false},
        {
          text: 'Actions',
          value: 'actions',
          align: 'center',
          cellClass: 'disable-wrap',
          sortable: false,
          filterable: false
        },
      ]"
    ></PayrollTableEmployee>
  </div>
</template>

<script>
export default {
  name: 'Employees',

  head() {
    return {
      title: 'Employees',
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



