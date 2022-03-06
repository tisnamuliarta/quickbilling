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
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        ></v-file-input>
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Company Name"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Address Name"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="City Name"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="State Name"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Country Name"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Zip Code Name"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Phone"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Fax"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Tax Number"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Website"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Email"
          placeholder="Product Name"
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
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Bank Branch"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Bank Address"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Bank Account Number"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Bank Account Name"
          placeholder="Product Name"
          outlined
          dense
          hide-details="auto"
        />
      </v-col>
      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
        <v-text-field
          label="Swift Code"
          placeholder="Product Name"
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
  name: 'EmailSetup',
}
</script>
