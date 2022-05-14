<template>
  <AgGrid ref="grid" :column-defs="columnDefs"></AgGrid>
</template>

<script>
import AgGrid from "./AgGrid";
export default {
  name: "DocumentGrid",
  components: {
    AgGrid
  },

  data() {
    return {
      columnDefs: [
        { field: 'product' },
        { field: 'description' },
        { field: 'qty' },
        { field: 'rate' },
        { field: 'amount' },
        { field: 'tax' },
      ]
    }
  }
}
</script>
