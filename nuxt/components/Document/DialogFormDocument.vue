<template>
  <div>
    <DialogForm
      ref="dialogForm"
      max-width="1200px"
      :dialog-title="formTitle"
      button-title="Save"
    >
      <template #content>
        <DocumentFormDocument ref="formDocument"></DocumentFormDocument>
      </template>
      <template #addLine>
        <v-menu
          offset-y
        >
          <template v-slot:activator="{ attrs, on }">
            <v-btn
              class="white--text"
              color="primary"
              dark
              v-bind="attrs"
              small
              v-on="on"
            >
              Print & Preview
              <v-icon
                right
                dark
              >
                mdi-printer
              </v-icon>
            </v-btn>
          </template>

          <v-list>
            <v-list-item
              v-for="item in items"
              :key="item"
              link
            >
              <v-list-item-title v-text="item"></v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>

        <v-menu
          offset-y
        >
          <template v-slot:activator="{ attrs, on }">
            <v-btn
              class="white--text ml-5"
              color="primary"
              dark
              v-bind="attrs"
              small
              v-on="on"
            >
              Action
              <v-icon
                right
                dark
              >
                mdi-content-copy
              </v-icon>
            </v-btn>
          </template>

          <v-list>
            <v-list-item
              v-for="item in items"
              :key="item"
              link
            >
              <v-list-item-title v-text="item"></v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>
      <template #saveData>
        <v-btn
          color="green darken-1"
          dark
          small
          :loading="submitLoad"
          @click="save()"
        >
          {{ buttonTitle }}
        </v-btn>
      </template>
    </DialogForm>
  </div>
</template>

<script>

export default {
  name: 'DialogFormDocument',

  props: {
    formTitle: {
      type: String,
      default: '',
    },
    type: {
      type: String,
      default: '',
    },
    buttonTitle: {
      type: String,
      default: '',
    },
    formData: {
      type: Object,
      default() {
        return {}
      },
    },
  },

  data() {
    return {
      dialog: false,
      submitLoad: false,
      form: this.formData,
      items: ['Item 1', 'Item 2'],
      url: '/api/sales/quote',
    }
  },

  methods: {
    newData(form, defaultItem) {
      this.$refs.dialogForm.openDialog()
      this.form = Object.assign({}, defaultItem)
      setTimeout(() => {
        this.$refs.formDocument.setData(this.form)
      }, 300)
      this.statusProcessing = 'insert'
    },

    editItem(item, url) {
      this.form = Object.assign({}, item)
      this.statusProcessing = 'update'
      this.$refs.dialogForm.openDialog()
      setTimeout(() => {
        this.$refs.formDocument.setData(this.form)
      }, 300)
    },

    close() {
      this.$refs.dialogForm.closeDialog()
      this.statusProcessing = 'insert'
      setTimeout(() => {
        this.form = Object.assign({}, this.defaultItem)
      }, 300)
    },

    save() {
      const vm = this
      const status = this.statusProcessing

      let data = this.$refs.formDocument.returnData()

      if (status === 'insert') {
        this.store('post', this.url, data)
        vm.submitLoad = false
      } else if (status === 'update') {
        this.store('put', this.url + '/' + this.form.id, data)
        vm.submitLoad = false
      }
    },

    store(method, url, data) {
      const vm = this
      vm.submitLoad = true
      this.$axios({method, url, data})
        .then((res) => {
          this.$refs.dialogForm.closeDialog()
          this.$emit('getDataFromApi')
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })

          vm.submitLoad = false
        })
    },
  },
}
</script>
