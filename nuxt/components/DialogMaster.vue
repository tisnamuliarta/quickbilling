<template>
  <div>
    <DialogForm
      ref="dialogForm"
      :max-width="maxWidth"
      :dialog-title="dialogTitle"
    >
      <template #content>
        <div class="scroll-container">
          <LazyTableSimple ref="childDetails" @removeData="removeData"/>
        </div>
      </template>
      <template #addLine>
        <v-btn
          small
          dark
          color="orange darken-1"
          class="white--text"
          @click="$refs.childDetails.addLine()"
        >
          Add Line
        </v-btn>
      </template>
      <template #saveData>
        <v-btn
          color="green darken-1"
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
      type: '',
    }
  },

  methods: {
    openDialogForm(url, title, type) {
      this.url = url
      this.type = type
      this.dialogTitle = title
      this.$refs.dialogForm.openDialog()
      this.getData()
    },

    getData() {
      this.$axios.get(this.url, {
        params: {
          type: this.type
        }
      }).then((res) => {
        this.$emit('emitData', {
          item: res.data.data.simple,
          type: this.type
        })
        this.$refs.childDetails.setDataToDetails(
          res.data.data.rows,
          res.data.data.header
        )
      })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
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
          type: this.type,
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
