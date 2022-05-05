<template>
  <v-row no-gutters>
    <v-col cols="12" class="pr-md-3 pl-md-3" md="6">
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Invoice Number Prefix"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-radio-group dense row label="Invoice Number Format">
          <v-radio label="Number Based (000001)" value="radio-1"></v-radio>
          <v-radio label="000001-YY" value="radio-1"></v-radio>
          <v-radio label="000001/MM/YYYY" value="radio-1"></v-radio>
          <v-radio
            label="Year Based (YYYY/000001)"
            value="radio-2"
          ></v-radio>
        </v-radio-group>
      </v-col>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'EmailSetup',

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
    }
  },

  methods: {
    getForm() {
      return this.form
    },

    setForm(form) {
      this.form = Object.assign({}, form)
    }
  }
}
</script>
