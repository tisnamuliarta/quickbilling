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
      title: 'A/P Invoice'
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
