<template>
  <LazyFormDialogFull ref="dialog" @close="close">
    <template #content>
      <LazyDocumentFormInput ref="formDocument"></LazyDocumentFormInput>
    </template>
    <template #actions>
      <v-btn
        color="green darken-1"
        class="mr-3"
        dark
        rounded
        @click="close"
      >
        Save

        <v-menu
          transition="slide-y-transition"
          bottom
        >
          <template #activator="{ on, attrs }">
            <v-btn
              dark
              icon
              v-bind="attrs"
              v-on="on"
            >
              <v-icon>mdi-menu-down</v-icon>
            </v-btn>
          </template>
          <v-list>
            <v-list-item
              v-for="(value, i) in items"
              :key="i"
            >
              <v-list-item-content>
                <v-list-item-title>{{ value.text }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-btn>
    </template>
  </LazyFormDialogFull>
</template>

<script>
export default {
  name: "quotation",

  data() {
    return {
      title: 'Sales Quotations',
      items: [
        {text: 'Edit', action: 'edit'},
        {text: 'Delete', action: 'delete'},
      ],
    }
  },

  mounted() {
    const data = {
      item: {
        title: this.title
      }
    }
    this.$refs.dialog.openDialog(data)
  },

  methods: {
    close() {
      this.$router.back()
      this.$nuxt.$emit('getDataFromApi')
    },
  }
}
</script>
