<template>
  <v-card>
    <v-toolbar flat color='grey lighten-2' dense style='height: 32px' class='toolbar-content'>
      <v-breadcrumbs
        :items='breadcrumb'
        divider='/'
        class="hidden-sm-and-down"
      ></v-breadcrumbs>

      <v-spacer class='hidden-xs-only'></v-spacer>
      <slot name="action-toolbar" />
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
