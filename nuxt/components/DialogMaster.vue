<template>
  <div>
    <DialogForm
      ref="dialogForm"
      :max-width="maxWidth"
      :dialog-title="dialogTitle"
    >
      <template #content>
        <div class="scroll-container">
          <LazyTableSimple ref="childDetails" @removeData="removeData" />
        </div>
      </template>
      <template #addLine>
        <v-btn
          x-small
          color="orange darken-1"
          class="white--text"
          @click="$refs.childDetails.addLine()"
        >
          <v-icon x-small> mdi-plus </v-icon>
        </v-btn>
      </template>
      <template #saveData>
        <v-btn
          color="blue darken-1"
          dark
          small
          :loading="submitLoad"
          @click="save()"
        >
          Save
        </v-btn>
      </template>
    </DialogForm>
  </div>
</template>

<script>
export default {
  name: 'DialogMaster',

  props: {
    maxWidth: {
      type: String,
      default: '500px',
    },
  },

  data() {
    return {
      submitLoad: false,
      url: '',
      dialogTitle: '',
    }
  },

  methods: {
    openDialogForm(url, title) {
      this.url = url
      this.dialogTitle = title
      this.$refs.dialogForm.openDialog()
      this.getData()
    },

    getData() {
      this.$axios.get(this.url).then((res) => {
        this.$refs.childDetails.setDataToDetails(
          res.data.data.rows,
          res.data.data.header
        )
      })
    },

    removeData(data) {
      this.$axios
        .delete(this.url + '/0', {
          params: {
            id: data.id,
          },
        })
        .then((res) => {
          this.$swal({
            type: 'success',
            title: 'Success!',
            text: res.data.message,
          })
          this.getData()
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
          this.getData()
        })
    },

    save() {
      this.submitLoad = true
      const details = this.$refs.childDetails.getAddData()

      this.$axios
        .post(this.url, {
          details,
        })
        .then((res) => {
          this.$swal({
            type: 'success',
            title: 'Success',
            text: res.data.message,
          })
          this.getData()
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
        .finally((res) => {
          this.submitLoad = false
        })
    },
  },
}
</script>
