<template>
  <v-form class="pt-2">
    <v-container>
    <component :is="selectComponent" ref="childComponent"></component>
    </v-container>
  </v-form>
</template>

<script>
import Company from './Company'
import Email from './Email'
import General from './General'
import Finance from './Finance'
import Pdf from './Pdf'

export default {
  name: 'FormSetup',

  components: {
    company: Company,
    email: Email,
    general: General,
    pdf: Pdf,
    finance: Finance,
  },

  data() {
    return {
      selectComponent: null,
    }
  },

  methods: {
    changeTab(alias) {
      this.selectComponent = this.$route.query.page
    },
  },
}
</script>
