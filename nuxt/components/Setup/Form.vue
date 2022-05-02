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
import Email from './Email'
import Tax from './Tax'
import General from './General'
import Finance from './Finance'
import Pdf from './Pdf'
import PaymentMethod from './PaymentMethod'
import PaymentTerm from './PaymentTerm'
import Currency from './Currency'
import AccountMapping from './AccountMapping'
import Roles from './Roles'
import Permissions from './Permissions'
import Users from './Users'

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
    account_mapping: AccountMapping,
    roles: Roles,
    permissions: Permissions,
    users: Users,
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
