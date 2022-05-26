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
          title: 'Business Overview',
          route: '/home/business-overview',
        },
        {
          title: 'Get Thing Done',
          route: '/home/getthingdone',
        },
      ],
      show: true,
      showBtn: false,
    })
  },
}
</script>
