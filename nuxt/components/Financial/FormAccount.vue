<template>
  <div>
    <DialogForm
      ref="dialogForm"
      max-width="500px"
      :dialog-title="formTitle"
      button-title="Save"
    >
      <template #content>
        <v-form class="pt-2">
          <v-container>
            <v-row no-gutters>
              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-select
                  v-model="form.category"
                  :items="itemCategory"
                  label="Category"
                  placeholder="Category"
                  outlined
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                  <template #prepend>
                    <v-btn small icon>
                      <v-icon
                        small
                        color="orange"
                        @click="
                            $refs.formMaster.openForm(
                              '/api/master/categories',
                              'Account Category',
                              'Account Category',
                              '400px'
                            )
                          "
                      >
                        mdi-arrow-right-bold
                      </v-icon>
                    </v-btn>
                  </template>
                </v-select>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.name"
                  label="Name"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.number"
                  label="Number"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-select
                  v-model="form.details"
                  :items="['None', 'Sub Account Of:', 'Header Account Of:']"
                  label="Details"
                  outlined
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                </v-select>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-select
                  v-model="form.tax"
                  :items="itemTax"
                  label="Default Account Tax"
                  outlined
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                  <template #prepend>
                    <v-btn small icon>
                      <v-icon
                        small
                        color="orange"
                        @click="
                            $refs.formMaster.openForm(
                              '/api/master/categories',
                              'Tax Category',
                              'Tax Category',
                              '400px'
                            )
                          "
                      >
                        mdi-arrow-right-bold
                      </v-icon>
                    </v-btn>
                  </template>
                </v-select>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-select
                  v-model="form.bank"
                  :items="itemBank"
                  label="Bank Name"
                  outlined
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                  <template #prepend>
                    <v-btn small icon>
                      <v-icon
                        small
                        color="orange"
                        @click="
                            $refs.formMaster.openForm(
                              '/api/master/banks',
                              'Banks',
                              'Banks',
                              '800px'
                            )
                          "
                      >
                        mdi-arrow-right-bold
                      </v-icon>
                    </v-btn>
                  </template>
                </v-select>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.opening_balance"
                  label="Opening Balance"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-textarea
                  v-model="form.descriptions"
                  label="Descriptions"
                  outlined
                  dense
                  hide-details="auto"
                ></v-textarea>
              </v-col>

            </v-row>
          </v-container>
        </v-form>
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

    <LazyItemFormMaster ref="formMaster" @returnData="returnData"></LazyItemFormMaster>
  </div>
</template>

<script>
export default {
  name: 'FormAccount',

  props: {
    formTitle: {
      type: String,
      default: '',
    },
    buttonTitle: {
      type: String,
      default: '',
    },
    url: {
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
      itemCategory: [],
      itemBank: [],
      itemTax: [],
      statusProcessing: 'insert',
    }
  },

  mounted() {
    this.getCategory()
  },

  methods: {
    newData() {
      this.$refs.dialogForm.openDialog()
      this.statusProcessing = 'insert'
      this.form = Object.assign({}, this.defaultItem)
    },

    editItem(item) {
      this.form = Object.assign({}, item)
      this.statusProcessing = 'update'
      this.$refs.dialogForm.openDialog()
    },

    getCategory() {
      this.$axios.get(`/api/master/categories`, {
        params: {
          type: 'Account Category'
        }
      }).then((res) => {
        this.itemCategory = res.data.data.simple
      })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })

      this.$axios.get(`/api/master/banks`, {
        params: {
          type: 'Banks'
        }
      }).then((res) => {
        this.itemBank = res.data.data.simple
      })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    returnData(data) {
      if (data.type === 'Banks') {
        this.itemBank = data.item
      } else if (data.type === 'Item Unit') {
        this.itemUnit = data.item
      }
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
      const form = this.form
      const status = this.statusProcessing
      const data = {
        form,
        status,
      }

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
