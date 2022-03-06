<template>
  <LazyDialogMaster ref="dialogMaster" :url="url" max-width="400px" />
</template>

<script>
export default {
  name: 'ProductBrand',

  data() {
    return {
      url: '',
    }
  },

  methods: {
    openForm(url, title) {
      this.url = url
      this.$refs.dialogMaster.openDialogForm(url, title)
    },
  },
}
</script>
