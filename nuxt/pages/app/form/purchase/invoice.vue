<template>
  <div>
    <LazyDocumentDialogForm
      ref="dialogForm"
      url="/api/documents/form"
      form-type="PI"
      dialog-title="AP Invoice"
      @getDataFromApi="getDataFromApi"
    ></LazyDocumentDialogForm>
  </div>
</template>

<script>
export default {
  name: 'SalesQuotation',

  // activated() {
  //   this.$refs.dialogForm.getDataFromApi()
  // },

  methods: {
    getDataFromApi() {
      this.$nuxt.$emit('getDataFromApi')
    }
  }
}
</script>
