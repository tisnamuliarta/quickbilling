<template>
  <div>
    <LazyDocumentFormInput ref="formDocument"></LazyDocumentFormInput>
  </div>
</template>

<script>
export default {
  name: "quotation",
  layout: 'dialog',

  data() {
    return {
      title: 'Sales Quotations'
    }
  },

  head() {
    return {
      title: this.title,
    }
  },

  mounted() {
    this.$nuxt.$emit('setTitle', this.title)
  },
}
</script>
