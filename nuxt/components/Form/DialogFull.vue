<template>
  <v-dialog
    v-model="dialog"
    fullscreen
    hide-overlay
    transition="dialog-top-transition"
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
          @click="dialog = false"
        >
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-card-title>
      <v-divider/>

      <slot name="content"/>

      <v-divider />
      <v-card-actions>
        <v-spacer />
        <v-btn
          color="primary"
          small
          @click="dialog = false"
        >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "DialogFull",

  data() {
    return {
      dialog: false,
      title: '',
    }
  },

  methods: {
    openDialog(data) {
      this.dialog = true
      this.title = data.item.text
    }
  }
}
</script>
