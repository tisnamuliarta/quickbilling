<template>
  <div>
    <LazyDocumentDialogForm
      ref="dialogForm"
      url="/api/documents/form"
      form-type="GR"
      dialog-title="Goods Return"
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
