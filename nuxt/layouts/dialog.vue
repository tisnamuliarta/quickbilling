<template>
  <v-dialog
    v-model="dialog"
    fullscreen
    hide-overlay
    transition="dialog-top-transition"
    scrollable
  >
    <v-card tile>
      <v-card-title>
        <v-toolbar-title>
          <v-btn icon>
            <v-icon>mdi-progress-pencil</v-icon>
          </v-btn>
          <span v-text="title"></span>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn
          icon
          dark
          color="red"
          @click="close"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-card-title>
      <v-divider/>

      <v-card-text class="pl-0 pr-0">
        <v-container fluid>
          <Nuxt />
        </v-container>
      </v-card-text>

      <v-divider />

      <v-card-actions>
        <v-spacer />
        <v-btn
          color="green darken-1"
          class="mr-3"
          dark
          small
          @click="close"
        >
          Done
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'DialogLayout',
  middleware: 'auth',
  data() {
    return {
      dialog: true,
      title: ''
    }
  },

  created() {
    this.$nuxt.$on('setTitle', ($event) => this.setTitle($event))
  },

  methods: {
    setTitle(data) {
      this.title = data
    },

    close() {
      this.$router.back()
    },
  },
}
</script>
