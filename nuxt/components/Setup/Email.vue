<template>
  <v-row no-gutters>
    <v-col cols="12" class="pr-md-3 pl-md-3" md="6">
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Mailer"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Host"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Port"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Username"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Password"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-select
          :items="['null', 'ssl', 'tls']"
          label="Encryption"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        >
        </v-select>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Mail From Address"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
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
