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
                <v-autocomplete
                  v-model="form.status"
                  :items="itemStatus"
                  label="Status"
                  outlined
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                </v-autocomplete>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.period_count"
                  label="Period Count"
                  outlined
                  dense
                  type="number"
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.calendar_year"
                  label="Year"
                  outlined
                  dense
                  type="number"
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-menu
                  ref='menu3'
                  v-model='menu3'
                  :close-on-content-click='false'
                  transition='scale-transition'
                  offset-y
                  min-width='290px'
                >
                  <template #activator='{ on, attrs }'>
                    <v-text-field
                      v-model="form.closing_date"
                      label="Closing Date"
                      prepend-icon='mdi-calendar'
                      readonly
                      persistent-hint
                      outlined dense hide-details='auto'
                      v-bind='attrs'
                      v-on='on'
                    ></v-text-field>
                  </template>

                  <v-date-picker
                    v-model="form.closing_date"
                    no-title
                    @input='menu3 = false'
                  >
                  </v-date-picker>
                </v-menu>
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

    <LazyInventoryFormMaster ref="formMaster" @returnData="returnData"></LazyInventoryFormMaster>
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
      itemStatus: [],
      itemBank: [],
      itemTax: [],
      menu3: '',
      statusProcessing: 'insert',
    }
  },

  methods: {
    newData(item) {
      this.$refs.dialogForm.openDialog()
      this.statusProcessing = 'insert'
      this.form = Object.assign({}, item)
      this.itemStatus = this.form.status_list
    },

    editItem(item, form) {
      this.$refs.dialogForm.openDialog()
      this.form = Object.assign({}, item)
      this.statusProcessing = 'update'
      this.itemStatus = form.status_list
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
      const status = this.statusProcessing

      if (status === 'insert') {
        this.store('post', this.url, this.form)
        vm.submitLoad = false
      } else if (status === 'update') {
        this.store('put', this.url + '/' + this.form.id, this.form)
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
