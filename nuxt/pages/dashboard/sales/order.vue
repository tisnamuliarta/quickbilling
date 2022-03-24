<template>
  <div>
    <DocumentTableDocument type-document="SO"></DocumentTableDocument>
  </div>
</template>

<script>
export default {
  name: 'SalesOrder',
  layout: 'dashboard',

  head() {
    return {
      title: 'Sales Order',
    }
  },
}
</script>
