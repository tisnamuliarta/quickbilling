<template>
  <div>
    <DialogForm
      ref="dialogForm"
      max-width="800px"
      :dialog-title="formTitle"
      button-title="Save"
    >
      <template #content>
        <v-form class="pt-2">
          <v-container>
            <v-row dense>
              <v-col cols="12" md="4">
                <v-text-field
                  v-model="form.first_name"
                  label="First name"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field
                  v-model="form.middle_name"
                  label="Middle name"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="4">
                <v-text-field
                  v-model="form.last_name"
                  label="Last name"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="8">
                <v-text-field
                  v-model="form.email"
                  label="Email"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12">
                <v-card flat>
                  <v-tabs
                    v-model="tab"
                  >
                    <v-tab
                      v-for="item in items"
                      :key="item.tab"
                      :href="item.href"
                    >
                      {{ item.tab }}
                    </v-tab>
                  </v-tabs>

                  <v-tabs-items v-model="tab">
                    <v-tab-item value="tab-1">
                      <v-row dense class="mt-2">
                        <v-col cols="12" md="4">
                          <v-menu
                            ref="menu3"
                            v-model="menu3"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                          >
                            <template #activator="{ on, attrs }">
                              <v-text-field
                                v-model="form.birth_date"
                                label="Birth Date"
                                prepend-icon="mdi-calendar"
                                readonly
                                persistent-hint
                                outlined
                                dense
                                hide-details="auto"
                                v-bind="attrs"
                                v-on="on"
                              ></v-text-field>
                            </template>

                            <v-date-picker
                              v-model="form.birth_date"
                              no-title
                              @input="menu3 = false"
                            >
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.gender"
                            label="Gender"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12">
                          <v-text-field
                            v-model="form.address"
                            label="Address"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.city"
                            label="City"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.zip_code"
                            label="Zip Code"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.home_phone"
                            label="Home Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.work_phone"
                            label="Work Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.mobile_phone"
                            label="Mobile Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.national_id"
                            label="National ID"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-tab-item>

                    <v-tab-item value="tab-2">
                      <v-row dense class="mt-2">
                        <v-col cols="12" md="4">
                          <v-menu
                            ref="menu3"
                            v-model="menu3"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                          >
                            <template #activator="{ on, attrs }">
                              <v-text-field
                                v-model="form.birth_date"
                                label="Birth Date"
                                prepend-icon="mdi-calendar"
                                readonly
                                persistent-hint
                                outlined
                                dense
                                hide-details="auto"
                                v-bind="attrs"
                                v-on="on"
                              ></v-text-field>
                            </template>

                            <v-date-picker
                              v-model="form.birth_date"
                              no-title
                              @input="menu3 = false"
                            >
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.gender"
                            label="Gender"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12">
                          <v-text-field
                            v-model="form.address"
                            label="Address"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.city"
                            label="City"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.zip_code"
                            label="Zip Code"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.home_phone"
                            label="Home Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.work_phone"
                            label="Work Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.mobile_phone"
                            label="Mobile Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.national_id"
                            label="National ID"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-tab-item>

                    <v-tab-item value="tab-3">
                      <v-row dense class="mt-2">
                        <v-col cols="12" md="4">
                          <v-menu
                            ref="menu3"
                            v-model="menu3"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                          >
                            <template #activator="{ on, attrs }">
                              <v-text-field
                                v-model="form.birth_date"
                                label="Birth Date"
                                prepend-icon="mdi-calendar"
                                readonly
                                persistent-hint
                                outlined
                                dense
                                hide-details="auto"
                                v-bind="attrs"
                                v-on="on"
                              ></v-text-field>
                            </template>

                            <v-date-picker
                              v-model="form.birth_date"
                              no-title
                              @input="menu3 = false"
                            >
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.gender"
                            label="Gender"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12">
                          <v-text-field
                            v-model="form.address"
                            label="Address"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.city"
                            label="City"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.zip_code"
                            label="Zip Code"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.home_phone"
                            label="Home Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.work_phone"
                            label="Work Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.mobile_phone"
                            label="Mobile Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.national_id"
                            label="National ID"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-tab-item>

                    <v-tab-item value="tab-4">
                      <v-row dense class="mt-2">
                        <v-col cols="12" md="4">
                          <v-menu
                            ref="menu3"
                            v-model="menu3"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                          >
                            <template #activator="{ on, attrs }">
                              <v-text-field
                                v-model="form.birth_date"
                                label="Birth Date"
                                prepend-icon="mdi-calendar"
                                readonly
                                persistent-hint
                                outlined
                                dense
                                hide-details="auto"
                                v-bind="attrs"
                                v-on="on"
                              ></v-text-field>
                            </template>

                            <v-date-picker
                              v-model="form.birth_date"
                              no-title
                              @input="menu3 = false"
                            >
                            </v-date-picker>
                          </v-menu>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.gender"
                            label="Gender"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12">
                          <v-text-field
                            v-model="form.address"
                            label="Address"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.city"
                            label="City"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.zip_code"
                            label="Zip Code"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.home_phone"
                            label="Home Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.work_phone"
                            label="Work Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.mobile_phone"
                            label="Mobile Phone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                          <v-text-field
                            v-model="form.national_id"
                            label="National ID"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>
                      </v-row>
                    </v-tab-item>

                  </v-tabs-items>
                </v-card>
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
  </div>
</template>

<script>

export default {
  name: 'FormContact',

  props: {
    formTitle: {
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
      show: false,
      form: this.formData,
      defaultItem: this.formData,
      itemCategory: [],
      itemUnit: [],
      itemAccounts: [],
      itemBank: [],
      itemPaymentTerm: [],
      statusProcessing: 'insert',
      menu3: '',
      valueWhenIsEmpty: '0',
      url: '/api/bp/contacts',
      moneyOptions: {
        suffix: "",
        length: 11,
        precision: 2
      },
      tab: null,
      items: [
        {tab: 'Personal Info', href: '#tab-1'},
        {tab: 'Payment method', href: '#tab-2'},
        {tab: 'Employment details', href: '#tab-3'},
        {tab: 'Pay types', href: '#tab-4'},
      ],
    }
  },

  mounted() {
    this.getAccounts()
    this.getPaymentTerms()
    this.getBanks()
  },

  methods: {
    newData(form, defaultItem) {
      this.$refs.dialogForm.openDialog()
      this.statusProcessing = 'insert'
      this.form = Object.assign({}, defaultItem)
    },

    editItem(item) {
      this.form = Object.assign({}, item)
      this.statusProcessing = 'update'
      this.$refs.dialogForm.openDialog()
    },

    addLine(type) {
      if (type === 'email') {
        this.form.emails.push({
          email: null,
        })
      } else if (type === 'bank') {
        this.form.banks.push({
          name: null,
          branch: null,
          contact_account_name: null,
          contact_account_number: null,
        })
      }
    },

    removeLine(index, type, item) {
      if (type === 'email') {
        this.form.emails.splice(index, 1)
        this.$axios.delete(`/api/bp/delete-email/` + item.email)
      } else if (type === 'bank') {
        this.form.banks.splice(index, 1)
        this.$axios.delete(`/api/bp/delete-bank/` + item.id)
      }
    },

    getAccounts() {
      this.$axios.get(`/api/financial/accounts`, {
        params: {
          type: "All"
        }
      })
        .then((res) => {
          this.itemAccounts = res.data.data.rows
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    getPaymentTerms() {
      this.$axios.get(`/api/financial/payment-terms`, {
        params: {
          type: "All"
        }
      })
        .then((res) => {
          this.itemPaymentTerm = res.data.data.auto_complete
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    getBanks() {
      this.$axios.get(`/api/master/banks`, {
        params: {
          type: "All"
        }
      })
        .then((res) => {
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
      if (data.type === 'Item Category') {
        this.itemCategory = data.item
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
