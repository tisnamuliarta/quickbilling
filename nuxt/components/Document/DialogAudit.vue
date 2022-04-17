<template>
  <DialogForm
    ref="dialogForm"
    max-width="1000px"
    dialog-title="Send Email"
    button-title="Save"
  >
    <template #content>
      <v-data-table
        :headers="headers"
        :items="items"
        dense
        :items-per-page="5"
        class="elevation-1 item-baseline"
      >
        <template #[`item.old_values`]="{ item }">
          <span v-for="(value, index) in item.old_values">
            {{ index }} = {{ value }}
            <v-divider />
          </span>
        </template>

        <template #[`item.new_values`]="{ item }">
          <span v-for="(value, index) in item.new_values">
            {{ index }} = {{ value }}
            <v-divider />
          </span>
        </template>
      </v-data-table>
    </template>
  </DialogForm>
</template>

<script>
export default {
  name: "DialogAudit",

  data() {
    return {
      items: [],
      headers: [
        {text: 'Event', value: 'event'},
        {text: 'Old Value', value: 'old_values'},
        {text: 'New Value', value: 'new_values'},
        {text: 'User', value: 'user.name'},
        {text: 'Date', value: 'created_at'},
      ]
    }
  },

  methods: {
    openDialogAudit(items) {
      this.$refs.dialogForm.openDialog()
      this.items = items
    },
  }
}
</script>

<style scoped>
.item-baseline > .v-data-table__wrapper > table > tbody > tr {
  vertical-align: baseline !important;
}
</style>
