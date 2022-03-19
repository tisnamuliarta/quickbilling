<template>
  <v-card>
    <v-toolbar flat color='grey lighten-2' dense style='height: 32px' class='toolbar-content'>
      <v-breadcrumbs
        :items='breadcrumb'
        divider='/'
      ></v-breadcrumbs>

      <v-spacer class='hidden-xs-only'></v-spacer>
      <v-btn color="green" small dark outlined @click="getDataFromApi">
        refresh
        <v-icon>mdi-refresh-circle</v-icon>
      </v-btn>
    </v-toolbar>

    <slot name="content"></slot>

    <v-divider></v-divider>
    <v-card-actions>
      <slot name="action"></slot>
      <v-spacer></v-spacer>
      <slot name="saveData"></slot>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  name: "FormWindow",

  props: {
    breadcrumb: {
      type: Array,
      default() {
        return []
      }
    }
  },

  methods: {
    getDataFromApi() {
      this.$emit('getDataFromApi')
    }
  }
}
</script>
