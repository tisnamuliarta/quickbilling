<template>
  <v-form class="pt-2">
    <v-container>
    <component :is="selectComponent" ref="childComponent" :formData="form"></component>
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
      form: {},
    }
  },

  methods: {
    changeTab(form, url) {
      this.selectComponent = this.$route.query.page
      this.form = Object.assign({}, form)
      setTimeout(() => {
        this.$refs.childComponent.setForm(this.form, url)
      }, 300)
    },

    getForm() {
      return this.$refs.childComponent.getForm()
    },
  },
}
</script>
