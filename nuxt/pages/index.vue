<template>
  <div>Home</div>
</template>

<script>
export default {
  data() {
    return {
      title: 'Dashboard',
    }
  },

  mounted() {
    this.$nuxt.$emit('extensionSetting', {
      tabs: [
        {
          title: 'Get Thing Done',
          route: '/app/getthingdone',
        },
        {
          title: 'Business Overview',
          route: '/app/business-overview',
        },
      ],
      show: true,
      showBtn: false,
    })
  },
}
</script>
