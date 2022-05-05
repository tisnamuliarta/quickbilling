<template>
  <v-form class="pt-2">
    <v-layout wrap>
      <v-flex md12 class="d-flex">
        <v-layout wrap>
          <v-flex md12 class="pa-1 mb-1">
            <v-text-field
              label="General Name"
              placeholder="Product Name"
              outlined
              dense
              hide-details="auto"
            ></v-text-field>
          </v-flex>
        </v-layout>
      </v-flex>
    </v-layout>
  </v-form>
</template>

<script>
export default {
  name: 'EmailSetup',
}
</script>
