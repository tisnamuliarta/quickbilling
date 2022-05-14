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
import Email from './Email'
import General from './General'
import Finance from './Finance'
import Pdf from './Pdf'
import Roles from './Roles'
import Permissions from './Permissions'
import Users from './Users'

export default {
  name: 'FormSetup',

  components: {
    email: Email,
    general: General,
    pdf: Pdf,
    finance: Finance,
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
