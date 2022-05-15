<template>
  <div>
    <DocumentTableDocument type-document="SI"></DocumentTableDocument>
  </div>
</template>

<script>
export default {
  name: 'SalesInvoice',
  layout: 'dashboard',

  head() {
    return {
      title: 'A/R Invoice',
    }
  },
}
</script>
