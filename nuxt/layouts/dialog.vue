<template>
  <v-app>
    <v-dialog
      v-model="dialog"
      fullscreen
      hide-overlay
      persistent
      transition="dialog-top-transition"
      scrollable
    >
      <v-card tile height="100vh" width="100vw">
        <v-card-title>
          <v-toolbar-title>
            <v-btn icon>
              <v-icon>mdi-progress-pencil</v-icon>
            </v-btn>
            <span v-text="title"></span>
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon dark color="red" @click="close">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>
        <v-divider />

        <v-card-text class="pl-0 pr-0">
          <v-container fluid>
            <Nuxt keep-alive />
          </v-container>
        </v-card-text>

        <v-divider />

        <v-card-actions>
          <v-spacer />
          <v-btn
            color="green darken-1"
            class="mr-3"
            dark
            rounded
            @click="close"
          >
            Save

            <v-menu transition="slide-y-transition" bottom>
              <template #activator="{ on, attrs }">
                <v-btn dark icon v-bind="attrs" v-on="on">
                  <v-icon>mdi-menu-down</v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item v-for="(value, i) in items" :key="i">
                  <v-list-item-content>
                    <v-list-item-title>{{ value.text }}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
            </v-menu>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
export default {
  name: 'DialogLayout',
  middleware: 'auth',
  data() {
    return {
      dialog: true,
      title: '',
      items: [
        { text: 'Edit', action: 'edit' },
        { text: 'Delete', action: 'delete' },
      ],
      itemText: '',
      itemAction: '',
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
