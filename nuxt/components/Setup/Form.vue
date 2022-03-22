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
import Tax from './Tax'
import General from './General'
import Finance from './Finance'
import Pdf from './Pdf'
import PaymentMethod from './PaymentMethod'
import PaymentTerm from './PaymentTerm'
import Currency from './Currency'

export default {
  name: 'FormSetup',

  components: {
    company: Company,
    email: Email,
    general: General,
    pdf: Pdf,
    finance: Finance,
    taxes: Tax,
    term: PaymentTerm,
    payment: PaymentMethod,
    currency: Currency,
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
