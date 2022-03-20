<template>
<div>
  <DialogForm
    ref="dialogForm"
    max-width="800px"
    dialog-title="Item List"
    button-title="Save"
    original="top"
  >
    <template #content>
      <InventoryTableItem
        ref="tableItem"
        :view-data="viewData"
        :show-add-btn="showAddBtn"
      ></InventoryTableItem>
    </template>

    <template #saveData>
      <v-btn
        color="green darken-1"
        dark
        small
        @click="selectData"
      >
        Select Item
        <v-icon>mdi-arrow-down-right</v-icon>
      </v-btn>
    </template>
  </DialogForm>
</div>
</template>

<script>
export default {
  name: "DialogItem",

  props: {
    viewData: {
      type: Boolean,
      default: true
    },
    showAddBtn: {
      type: Boolean,
      default: true
    },
  },

  data() {
    return {
      row: null
    }
  },

  methods: {
    openDialog(row) {
      this.$refs.dialogForm.openDialog()
      setTimeout(() => {
        this.$refs.tableItem.setEmptyToSelected()
      }, 150)
      this.row = row
    },

    selectData() {
      this.$emit('selectItems', {
        row: this.row,
        selected: this.$refs.tableItem.returnSelected(),
      })
      this.$refs.dialogForm.closeDialog()
    }
  }
}
</script>
