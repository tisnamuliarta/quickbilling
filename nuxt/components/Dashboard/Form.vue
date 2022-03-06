<template>
  <v-layout>
    <v-flex xs12 md12>
      <v-container fluid> </v-container>
    </v-flex>
  </v-layout>
</template>
<script>
export default {
  name: 'FormDashboard',
  data() {
    return {
      paperType: '',
      items: [],
    }
  },

  created() {
    // this.homeData()
  },

  methods: {
    checkClinic(event) {
      return this.$gates.hasAnyRole('Superuser|Admin Klinik')
    },

    homeData() {
      this.$axios.get(`/api/master/papers`).then((res) => {
        this.items = res.data.data.items
      })
    },
  },
}
</script>
