<template>
  <v-app class="bg-auth">
    <v-main>
      <v-container transition="slide-x-transition" fluid fill-height>
        <router-view></router-view>
      </v-container>
    </v-main>
    <v-footer color="grey lighten-2" padless>
      <v-col class="text-center" cols="12">
        Copyright © {{ new Date().getFullYear() }} —
        <strong> Quickbilling </strong>
      </v-col>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data() {
    return {}
  },
}
</script>

<style scoped>
.bg-auth {
  background-color: #eee;
  background-repeat: no-repeat !important;
  background-size: cover !important;
}
</style>
