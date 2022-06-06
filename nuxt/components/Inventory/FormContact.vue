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
              <v-col cols="12" class="">
                <v-text-field
                  v-model="form.name"
                  label="Name"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="4" class="">
                <v-select
                  v-model="form.type"
                  :items="['Vendor', 'Customer', 'Employee', 'Other']"
                  label="Category"
                  outlined
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                </v-select>
              </v-col>

              <v-col cols="12" class="">
                <v-checkbox
                  v-model="form.can_login"
                  dense
                  hide-details
                  label="Can Login?"
                ></v-checkbox>
              </v-col>

              <v-col v-if="form.can_login" cols="12" md="6" class="">
                <v-text-field
                  v-model="form.email"
                  label="Email"
                  outlined
                  dense
                  hide-details="auto"
                  @click:append="show = !show"
                ></v-text-field>
              </v-col>

              <v-col v-if="form.can_login" cols="12" md="6" class="">
                <v-text-field
                  v-model="form.password"
                  label="Password"
                  :append-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
                  :type="show ? 'text' : 'password'"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" class="">
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
                        <v-col cols="12" md="3" class="">
                          <v-select
                            v-model="form.identify"
                            :items="['Mr.', 'Ms.']"
                            label="Title"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-select>
                        </v-col>
                        <v-col cols="12" md="3" class="">
                          <v-text-field
                            v-model="form.first_name"
                            label="First Name"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3" class="">
                          <v-text-field
                            v-model="form.middle_name"
                            label="Middle Name"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="3" class="">
                          <v-text-field
                            v-model="form.last_name"
                            label="Last Name"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="3" class="">
                          <v-select
                            v-model="form.identify_by"
                            :items="['Driver License', 'National ID', 'Passport']"
                            label="Identify By"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-select>
                        </v-col>

                        <v-col cols="12" md="9" class="">
                          <v-text-field
                            v-model="form.identify_number"
                            label="Identify Number"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6" class="">
                          <v-text-field
                            v-model="form.company_name"
                            label="Company Name"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6" class="">
                          <v-text-field
                            v-model="form.phone"
                            label="Handphone"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6" class="">
                          <v-text-field
                            v-model="form.fax"
                            label="Fax"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6" class="">
                          <v-text-field
                            v-model="form.tax_number"
                            label="Tax Number"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="12" class="">
                          <v-textarea
                            v-model="form.address"
                            rows="3"
                            label="Billing Address"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-textarea>
                        </v-col>

                        <v-col cols="12" md="12" class="">
                          <v-textarea
                            v-model="form.shipping_address"
                            rows="3"
                            label="Shipping Address"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-textarea>
                        </v-col>
                      </v-row>
                    </v-tab-item>

                    <v-tab-item value="tab-2">
                      <v-row
                        v-for="(item, index) in form.banks"
                        :key="index"
                        dense
                      >
                        <v-col
                          cols="11"
                          class=""
                        >
                          <v-col
                            cols="12"
                            class=""
                          >
                            <span>Bank Account</span>
                            <hr>
                          </v-col>
                          <v-col
                            cols="12"
                            class=""
                          >
                            <v-autocomplete
                              v-model="item.name"
                              :items="itemBank"
                              label="Bank Name"
                              outlined
                              dense
                              hide-details="auto"
                            ></v-autocomplete>
                          </v-col>

                          <v-col
                            cols="12"
                            class=""
                          >
                            <v-text-field
                              v-model="item.branch"
                              label="Bank Branch"
                              outlined
                              dense
                              hide-details="auto"
                            ></v-text-field>
                          </v-col>

                          <v-col
                            cols="12"
                            class=""
                          >
                            <v-text-field
                              v-model="item.contact_account_name"
                              label="Bank Holder Name"
                              outlined
                              dense
                              hide-details="auto"
                            ></v-text-field>
                          </v-col>

                          <v-col
                            cols="12"
                            class=""
                          >
                            <v-text-field
                              v-model="item.contact_account_number"
                              label="Bank Holder Account"
                              outlined
                              dense
                              hide-details="auto"
                            ></v-text-field>
                          </v-col>
                        </v-col>

                        <v-col cols="1" class="">
                          <v-btn
                            color="red darken-1"
                            dark
                            small
                            icon
                            @click="removeLine(index, 'bank', item)"
                          >
                            <v-icon>
                              mdi-delete
                            </v-icon>
                          </v-btn>
                        </v-col>
                      </v-row>

                      <v-col cols="12" class="">
                        <v-btn
                          color="green darken-1"
                          dark
                          small
                          @click="addLine('bank')"
                        >
                          Add Line
                        </v-btn>
                      </v-col>
                    </v-tab-item>

                    <v-tab-item value="tab-3">
                      <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-2">
                        <v-autocomplete
                          v-model="form.receivable_account_id"
                          :items="itemAccounts"
                          item-text="name"
                          item-value="id"
                          label="Account Receivable"
                          clearable
                          outlined
                          dense
                          hide-details="auto"
                        ></v-autocomplete>
                      </v-col>

                      <v-col cols="12" class="">
                        <v-autocomplete
                          v-model="form.payable_account_id"
                          :items="itemAccounts"
                          item-text="name"
                          item-value="id"
                          label="Account Payable"
                          clearable
                          outlined
                          dense
                          hide-details="auto"
                        ></v-autocomplete>
                      </v-col>

                      <v-col cols="12" class="">
                        <v-checkbox
                          v-model="form.active_max_payable"
                          dense
                          hide-details
                          label="Active Max Payable"
                        ></v-checkbox>
                      </v-col>

                      <v-col v-if="form.active_max_payable" cols="12" class="">
                        <vuetify-money
                          v-model="form.max_payable"
                          v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                          v-bind:options="moneyOptions"
                          label="Max Payable"
                          outlined
                          dense
                          hide-details="auto"
                        ></vuetify-money>
                      </v-col>

                      <v-col cols="12" class="">
                        <v-autocomplete
                          v-model="form.payment_term_id"
                          :items="itemPaymentTerm"
                          item-text="name"
                          item-value="id"
                          label="Default Payment Term"
                          clearable
                          outlined
                          dense
                          hide-details="auto"
                        ></v-autocomplete>
                      </v-col>
                    </v-tab-item>

                    <v-tab-item value="tab-4">
                      <v-row
                        v-for="(item, index) in form.emails"
                        :key="index"
                        dense
                        class="mt-2"
                      >
                        <v-col
                          cols="11"
                          class=""
                        >
                          <v-text-field
                            v-model="item.email"
                            label="Email"
                            outlined
                            dense
                            hide-details="auto"
                          ></v-text-field>
                        </v-col>

                        <v-col cols="1" class="">
                          <v-btn
                            color="red darken-1"
                            dark
                            small
                            icon
                            @click="removeLine(index, 'email', item)"
                          >
                            <v-icon>
                              mdi-delete
                            </v-icon>
                          </v-btn>
                        </v-col>
                      </v-row>

                      <v-col cols="12" class="">
                        <v-btn
                          color="green darken-1"
                          dark
                          small
                          @click="addLine('email')"
                        >
                          Add Line
                        </v-btn>
                      </v-col>
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
      valueWhenIsEmpty: '0',
      url: '/api/bp/contacts',
      moneyOptions: {
        suffix: "",
        length: 11,
        precision: 2
      },
      tab: null,
      items: [
        {tab: 'General Information', href: '#tab-1'},
        {tab: 'List Of Bank', href: '#tab-2'},
        {tab: 'Account Mapping', href: '#tab-3'},
        {tab: 'Email', href: '#tab-4'},
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
