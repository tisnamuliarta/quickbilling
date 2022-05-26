<template>
  <v-menu transition="slide-y-transition" offset-y bottom>
    <template #activator="{ on, attrs }">
      <v-btn dark small icon v-bind="attrs" v-on="on">
        <v-icon>mdi-menu-down</v-icon>
      </v-btn>
    </template>
    <v-list dense>
      <v-list-item
        v-for="(value, i) in items"
        :key="i"
        dense
        @click="makeInActive(value, doctype)"
      >
        <v-list-item-content>
          <v-list-item-title>{{ value }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script>
export default {
  data() {
    return {
      items: []
    }
  }
}
</script>
