<template>
  <v-row>
    <v-col cols="12" md="12">
      <LazySetupSectionAdvanceAccounting :form="form" />
      <hr>
    </v-col>

    <v-col cols="12" md="12">
      <LazySetupSectionAdvancedChartOfAccount :form="form" />
      <hr>
    </v-col>

    <v-col cols="12" md="12">
      <LazySetupSectionAdvancedCategory :form="form" />
      <hr>
    </v-col>

    <v-col cols="12" md="12">
      <LazySetupSectionAdvancedProject :form="form" />
      <hr>
    </v-col>

    <v-col cols="12" md="12">
      <LazySetupSectionAdvancedTimeTracking :form="form" />
      <hr>
    </v-col>

    <v-col cols="12" md="12">
      <LazySetupSectionAdvancedCurrency :form="form" />
      <hr>
    </v-col>

    <v-col cols="12" md="12">
      <LazySetupSectionAdvancedOther :form="form" />
      <hr>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'AdvanceSetting',

  props: {
    formData: {
      type: Object,
      default() {
        return {}
      },
    },
  },

  data() {
    return {
      form: this.formData,
      logo: '',
      itemCurrency: [],
      companyNameView: true,
      companyNameEdit: false,
    }
  },

  mounted() {
    this.getCurrency()
    this.companyNameView = true
  },

  methods: {
    save() {
      this.companyNameView = true
    },

    getCurrency() {
      this.$axios.get(`/api/financial/currency`).then((res) => {
        this.itemCurrency = res.data.data.rows
      })
    },

    changeCurrency() {
      const currency = this.form.company_currency_code
      this.form.company_currency_symbol = currency.symbol
      this.form.company_currency_code = currency.code
    },

    getForm() {
      let data = new FormData()
      Object.entries(this.form).forEach((entry) => {
        const [key, value] = entry
        data.append(key, value)
      })
      return data
    },

    setForm(form, url) {
      this.form = Object.assign({}, form)
      this.logo = url + '/files/logo/' + this.form.company_logo
    },
  },
}
</script>
