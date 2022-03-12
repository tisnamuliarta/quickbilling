<template>
  <LazyDialogMaster ref="dialogMaster"  @emitData="emitData" :max-width="maxWidth"></LazyDialogMaster>
</template>

<script>
export default {
  name: 'FormMaster',

  data() {
    return {
      url: '',
      maxWidth: '400px'
    }
  },

  methods: {
    emitData(data) {
      this.$emit('returnData', data)
    },

    openForm(url, title, type, maxWidth = '400px') {
      this.url = url
      this.maxWidth = maxWidth
      this.$refs.dialogMaster.openDialogForm(url, title, type)
    },
  },
}
</script>
