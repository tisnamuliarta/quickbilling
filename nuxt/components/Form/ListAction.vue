<template>
  <v-list dense>
    <span class="font-weight-bold subtitle-2 ml-4" v-text="title"></span>
    <v-divider class="ml-4 mb-2" />
    <v-list-item-group color="primary">
      <v-list-item
        v-for="(item, i) in items"
        :key="i"
        @click="processAction(item)"
      >
        <v-list-item-content>
          <v-list-item-title v-text="item.text"></v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list-item-group>
  </v-list>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: ''
    },
    items: {
      type: Array,
      default() {
        return []
      },
    },
  },

  methods: {
    processAction(item) {
      this.$emit('openAction', {
        item,
      })
      this.dialog = false
    },
  }
}
</script>
