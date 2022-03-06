<template>
  <span class="text-subtitle-2" v-text="title"></span>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: '',
    },
  },
}
</script>
