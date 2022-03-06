<template>
  <component :is="selectComponent" ref="childComponent"></component>
</template>

<script>
import Form from './Form'

export default {
  name: 'CheckDashboard',
  components: {
    eform: Form,
  },
  data() {
    return {
      selectComponent: null,
    }
  },

  created() {
    this.checkApp()
  },

  methods: {
    checkApp() {
      this.selectComponent = 'eform'
    },
  },
}
</script>
