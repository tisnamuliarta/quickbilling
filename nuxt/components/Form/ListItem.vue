<template>
  <v-card
    rounded
    elevation="1"
    class="mx-auto"
  >
    <v-card-title>
      <NuxtLink :to="url" v-text="title"></NuxtLink>
    </v-card-title>
    <v-card-text v-text="description"></v-card-text>
  </v-card>
</template>

<script>
export default {
  name: "ListItem",
  props: {
    title: {
      type: String,
      default: ''
    },
    description: {
      type: String,
      default: ''
    },
    url: {
      type: String,
      default: ''
    }
  }
}
</script>
