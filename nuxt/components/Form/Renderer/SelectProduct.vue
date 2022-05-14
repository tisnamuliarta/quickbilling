<template>
  <v-autocomplete
    dense
    solo
    :items="items"
    item-value="id"
    item-text="name"
  >
    <template #item="data">
      <v-list-item-content>
        <v-list-item-title v-text="data.item.name"></v-list-item-title>
        <v-list-item-subtitle v-text="data.item.description"></v-list-item-subtitle>
      </v-list-item-content>
    </template>
  </v-autocomplete>
</template>

<script>
export default {
  name: "SelectProduct",

  data() {
    return {
      items: []
    }
  },

  mounted() {
    this.getItems()
  },

  methods: {
    getItems() {
      this.$axios.get(`/api/inventory/items`)
        .then(res => {
          this.items = res.data.data.rows
        })
    }
  }
}
</script>
