<template>
  <span>Get Thing done</span>
</template>

<script>
export default {
  name: "GetThingDone",

  activated() {
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
      showBtn: false
    })
  },
}
</script>
