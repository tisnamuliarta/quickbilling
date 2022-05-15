<template>
  <v-chip
    link
    class="ma-2"
    color="primary"
    label
    small
    @click="$router.push({path: '/list'})"
  >
    <v-icon left> mdi-arrow-left</v-icon>
    All list
  </v-chip>
</template>

<script>
export default {
  name: "BackList"
}
</script>
