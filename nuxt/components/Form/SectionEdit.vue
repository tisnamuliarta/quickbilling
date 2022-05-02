<template>
  <v-card elevation="0">
    <v-form>
      <slot name="content"></slot>
    </v-form>
    <v-card-actions>
      <v-spacer />
      <v-btn outlined small @click="cancel"> Cancel </v-btn>
      <v-btn color="primary" small @click="processData"> Save </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      tabValue: 'company',
      loadingButton: false,
    }
  },
  methods: {
    save(form) {
      let options = {}
      let url = '/api/settings'

      this.loadingButton = true
      this.$axios
        .post(url, form)
        .then((res) => {
          this.loadingButton = false

          if (this.tabValue === 'company') {
            this.$nuxt.$emit('getLogo')
            this.$nuxt.$emit('getCompany')
          }

          // this.changeTabValue(this.tabValue)
        })
        .catch((err) => {
          this.loadingButton = false
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    cancel() {
      this.$emit('cancel')
    },
    processData() {
      this.$emit('save')
    },
  },
}
</script>
