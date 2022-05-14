<template>
  <span>
    <v-btn icon @click="deleteRow">
      <v-icon>mdi-delete</v-icon>
    </v-btn>
  </span>
</template>

<script>
export default {
  name: "ButtonDelete",
  methods: {
    deleteRow() {
      this.params.context.componentParent.methodFromParent(
        `Row: ${this.params.node.rowIndex}, Col: ${this.params.colDef.headerName}`
      );
    }
  }
}
</script>
