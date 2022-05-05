<template>
  <v-container fluid>
    <component
      :is="selectComponent"
      ref="childComponent"
      :formData="form"
    ></component>
  </v-container>
</template>

<script>
import Company from './Company'
import Advanced from './Advanced'
import Expenses from './Expenses'
import Payment from './Payment'
import Sales from './Sales'

export default {
  name: 'FormSetup',

  components: {
    company: Company,
    advanced: Advanced,
    expenses: Expenses,
    payment: Payment,
    sales: Sales,
  },

  data() {
    return {
      selectComponent: null,
      form: {},
    }
  },

  methods: {
    changeTab(form, url, page) {
      this.selectComponent = page
      this.form = Object.assign({}, form)
      setTimeout(() => {
        this.$refs.childComponent.setForm(this.form, url)
      }, 800)
    },

    getForm() {
      return this.$refs.childComponent.getForm()
    },
  },
}
</script>
