<template>
  <v-row no-gutters>
    <v-col cols="12" class="pr-md-3 pl-md-3" md="6">
      <v-subheader>Company Settings</v-subheader>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-divider/>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-file-input
          accept="image/*"
          label="Logo"
          placeholder="Logo"
          v-model="form.company_logo_temp"
          outlined
          dense
          hide-details="auto"
        ></v-file-input>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-img
          max-width="250"
          max-height="150"
          :src="logo"
        ></v-img>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Company Name"
          placeholder="Company Name"
          v-model="form.company_name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Address"
          v-model="form.company_address"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="City"
          v-model="form.company_city"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="State"
          v-model="form.company_state"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Country"
          v-model="form.company_country"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Zip Code"
          v-model="form.company_zip_code"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Phone"
          v-model="form.company_phone"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Fax"
          v-model="form.company_fax"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Tax Number"
          v-model="form.company_tax_number"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Website"
          v-model="form.company_website"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Email"
          v-model="form.company_email"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
    </v-col>
    <v-col cols="12" class="pr-md-3 pl-md-3" md="6">
      <v-subheader>Bank Account Detail</v-subheader>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-divider/>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Bank Name"
          v-model="form.company_bank_name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Bank Branch"
          v-model="form.company_bank_branch"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Bank Address"
          v-model="form.company_bank_address"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Bank Account Number"
          v-model="form.company_bank_account_number"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Bank Account Name"
          v-model="form.company_bank_account_name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Swift Code"
          v-model="form.company_bank_swift_code"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>

      <v-subheader>Additional Feature Settings</v-subheader>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-divider/>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-select
          label="Currency"
          v-model="form.company_currency_code"
          :items="itemCurrency"
          item-value="code"
          item-text="name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'CompanySetup',

  props: {
    formData: {
      type: Object,
      default() {
        return {}
      },
    }
  },

  data() {
    return {
      form: this.formData,
      logo: '',
      itemCurrency: []
    }
  },

  mounted() {
    this.getCurrency()
  },

  methods: {
    getCurrency() {
      this.$axios.get(`/api/financial/currency`)
      .then(res => {
        this.itemCurrency = res.data.data.simple
      })
    },

    getForm() {
      let data = new FormData()
      Object.entries(this.form).forEach(entry => {
        const [key, value] = entry
        data.append(key, value)
      })
      return data
    },

    setForm(form, url) {
      this.form = Object.assign({}, form)
      this.logo = url + '/files/logo/' + this.form.company_logo
    },
  }
}
</script>
