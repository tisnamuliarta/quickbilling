<template>
  <LazyFormDialogFull ref="dialogFull">
    <template #content>
      <LazyDocumentFormInput ref="formDocument" />
    </template>

    <template #actions>
      <v-card-actions>
        <v-spacer />
        <v-btn color="primary" small @click="dialog = false"> Save </v-btn>
      </v-card-actions>
    </template>
  </LazyFormDialogFull>
</template>

<script>
export default {
  name: 'Document',

  data() {
    return {
      form: {},
      defaultItem: {},
      audits: {},
    }
  },

  methods: {
    openDialog(data, id, copyFromId) {
      this.$refs.dialogFull.openDialog(data)

      this.getDataFromApi(data.item.action, data.item.type, id, copyFromId)
    },

    getDataFromApi(action, type, id, copyFromId = null) {
      this.showLoading = true
      const url =
        action === 'document' ? '/api/documents/form' : '/api/transactions/form'
      this.$axios
        .get(url + '/' + id, {
          params: {
            type,
            copyFromId,
          },
        })
        .then((res) => {
          let form =
            res.data.data.count > 0 ? res.data.data.rows : res.data.data.form
          this.audits = res.data.data.audits

          this.form = Object.assign({}, form)
          this.defaultItem = Object.assign({}, form)

          setTimeout(() => {
            this.$refs.formDocument.setData(this.form)
          }, 30)
        })
        .catch((err) => {
          const message =
            err.response !== undefined ? err.response.data.message : err
          this.$swal({
            type: 'error',
            title: 'Error',
            text: message,
          })
        })
        .finally((res) => {
          this.showLoading = false
        })
    },
  },
}
</script>
