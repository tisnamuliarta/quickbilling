<template>
  <div>
    <LazyDocumentFormInput ref="formDocument" />
  </div>
</template>

<script>
export default {
  name: "SalesOrder",
  layout: 'dialog',

  data() {
    return {
      title: 'Goods Return'
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
