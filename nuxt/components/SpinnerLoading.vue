<template>
  <v-dialog
    v-model="SpinnerLoading"
    no-click-animation
    persistent
    max-width="200px"
    transition="dialog-bottom-transition"
  >
    <v-card>
      <v-card-title class="headline grey lighten-2 card-title-style">
        Processing, Please Wait!
      </v-card-title>

      <v-card-text class="text-center" style="margin-top: 10px">
        <v-layout row wrap>
          <v-flex xs12>
            <v-progress-circular
              :size="40"
              :width="5"
              color="blue"
              indeterminate
            ></v-progress-circular>
          </v-flex>
        </v-layout>
        <!-- <div :style="{display: display}">
        </div> -->
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" text small @click="SpinnerLoading = false">
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'SpinnerLoading',
  data: () => ({
    SpinnerLoading: true,
  }),

  methods: {
    showLoadingSpinner() {
      this.SpinnerLoading = true
    },
    hideLoadingSpinner() {
      this.SpinnerLoading = false
    },
  },
}
</script>

<style scoped>
.card-title-style {
  font-size: 13px !important;
  margin-bottom: 15px !important;
}
</style>
