<template>
  <v-row no-gutters>
    <v-col cols="12" class="pr-md-3 pl-md-3" md="6">
      <v-subheader>General</v-subheader>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-divider/>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Font Size"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Logo"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Logo Width"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
      </v-col>

      <v-subheader>Signature</v-subheader>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-divider/>
      </v-col>

      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-radio-group dense row label="Show PDF Signature on Invoice">
          <v-radio label="Option 1" value="radio-1"></v-radio>
          <v-radio label="Option 2" value="radio-2"></v-radio>
        </v-radio-group>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-file-input
          accept="image/*"
          label="Logo"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-file-input>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Logo Width"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-text-field>
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

    <v-col cols="12" class="pr-md-3 pl-md-3" md="6">
      <v-subheader>Document Format</v-subheader>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-divider/>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-select
          :items="paperSize"
          label="Invoice"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-select>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-select
          :items="paperSize"
          label="Estimate"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-select>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-select
          :items="paperSize"
          label="Proposal"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-select>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-select
          :items="paperSize"
          label="Payment"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-select>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-select
          :items="paperSize"
          label="Credit Note"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-select>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-select
          :items="paperSize"
          label="Statement"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-select>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-select
          :items="paperSize"
          label="Contract"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-select>
      </v-col>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'EmailSetup',

  props: {
    form: {
      type: Object,
      default() {
        return {}
      },
    }
  },

  data() {
    return {
      paperSize: [
        'A4 Portrait',
        'A4 Landscape',
        'Letter Portrait',
        'Letter Landscape',
      ],
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
