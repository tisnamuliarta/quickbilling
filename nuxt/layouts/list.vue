<template>
  <v-app>
    <v-chip
      link
      class="ma-2"
      color="primary"
      label
      small
      @click="$router.push({path: '/dashboard/transactions/list'})"
    >
      <v-icon left> mdi-arrow-left</v-icon>
      All list
    </v-chip>
    <Nuxt/>
  </v-app>
</template>

<script>
export default {
  name: "listLayout",
  layout: 'dashboard'
}
</script>
