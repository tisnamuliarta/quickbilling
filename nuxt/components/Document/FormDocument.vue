<template>
  <div>
    <DialogForm
      ref="dialogForm"
      max-width="1200px"
      :dialog-title="formTitle"
      button-title="Save"
    >
      <template #content>
        <v-form class="pt-0">
          <v-container>
            <v-row no-gutters>
              <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.contact_id"
                  label="Customer/Vendor"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="2" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-select
                  v-model="form.issued_at"
                  :items="itemCategory"
                  label="Transaction Date"
                  outlined
                  multiple
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                </v-select>
              </v-col>

              <v-col cols="12" md="2" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-select
                  v-show="checkbox"
                  v-model="form.unit"
                  :items="itemUnit"
                  label="Shipping Date"
                  outlined
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                </v-select>
              </v-col>

              <v-col cols="12" md="2" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.name"
                  label="Transaction Number"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.name"
                  label="Tags"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <vuetify-money
                  v-model="form.purchase_price"
                  v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                  v-bind:options="moneyOptions"
                  label="Billing Address"
                  outlined
                  dense
                  hide-details="auto"
                ></vuetify-money>
              </v-col>

              <v-col cols="12" md="2" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-autocomplete
                  v-model="form.buy_account"
                  :items="itemAccounts"
                  item-text="name"
                  item-value="id"
                  label="Due Date"
                  outlined
                  dense
                  hide-details="auto"
                ></v-autocomplete>
              </v-col>

              <v-col cols="12" md="2" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <vuetify-money
                  v-show="checkbox"
                  v-model="form.sale_price"
                  v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                  v-bind:options="moneyOptions"
                  label="Ship Via"
                  outlined
                  dense
                  hide-details="auto"
                ></vuetify-money>
              </v-col>

              <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.buy_tax"
                  label="Reference No"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="2" class="pr-1 pl-1 pb-1">
                <v-checkbox
                  v-model="checkbox"
                  dense
                  hide-details
                  label="Shipping Info"
                  class="mt-0"
                ></v-checkbox>
              </v-col>

              <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-autocomplete
                  v-show="checkbox"
                  v-model="form.sell_account"
                  :items="itemAccounts"
                  item-text="name"
                  item-value="id"
                  label="Shipping Address"
                  outlined
                  dense
                  hide-details="auto"
                ></v-autocomplete>
              </v-col>

              <v-col cols="12" md="2" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.sell_tax"
                  label="Payment Term"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="2" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-autocomplete
                  v-show="checkbox"
                  v-model="form.inventory_account_name"
                  :items="itemAccounts"
                  item-text="name"
                  item-value="id"
                  label="Tracking No"
                  outlined
                  dense
                  hide-details="auto"
                ></v-autocomplete>
              </v-col>

              <v-col cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <vuetify-money
                  v-model="form.minimum_stock"
                  v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                  v-bind:options="moneyOptions"
                  label="Warehouse"
                  outlined
                  dense
                  hide-details="auto"
                ></vuetify-money>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-card>
                  <div class="scroll-container-min">
                    <LazyDocumentTableDetail ref="childDetails"/>
                  </div>
                  <v-card-actions>
                    <v-btn
                      color="blue darken-1"
                      dark
                      small
                      depressed
                      @click="$refs.childDetails.addLine()"
                    >
                      Add Line
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-col>

              <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <v-textarea
                    v-model="form.code"
                    rows="2"
                    label="Message"
                    outlined
                    dense
                    hide-details="auto"
                  ></v-textarea>
                </v-col>

                <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <v-textarea
                    v-model="form.code"
                    rows="2"
                    label="Memo"
                    outlined
                    dense
                    hide-details="auto"
                  ></v-textarea>
                </v-col>

                <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <v-file-input
                    v-model="form.code"
                    multiple
                    label="Memo"
                    outlined
                    dense
                    hide-details="auto"
                  ></v-file-input>
                </v-col>
              </v-col>

              <v-spacer class="hidden-sm-and-down"></v-spacer>

              <v-col cols="12" md="6" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-row no-gutters>

                  <v-col cols="12" md="7"></v-col>
                  <v-col cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Sub Total"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col cols="12" md="7"></v-col>

                  <v-col cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Discount Per Lines"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <v-select
                      v-model="form.code"
                      :items="['Amount', 'Percent']"
                      label="Discount Type"
                      outlined
                      dense
                      hide-details="auto"
                    ></v-select>
                  </v-col>

                  <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Discount Rate"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Discount Amount"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col v-show="tax" cols="12" md="7"></v-col>
                  <v-col v-show="tax" cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="PPN"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col v-show="checkbox" cols="12" md="7"></v-col>
                  <v-col v-show="checkbox" cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Shipping Fee"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col cols="12" md="7"></v-col>
                  <v-col cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Total"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col cols="12" md="2" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <v-checkbox
                      v-model="withholding"
                      dense
                      hide-details
                      label="Withholding"
                      class="mt-0"
                    ></v-checkbox>
                  </v-col>

                  <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <v-select
                      v-show="withholding"
                      v-model="form.code"
                      :items="['Amount', 'Percent']"
                      label="Type"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></v-select>
                  </v-col>

                  <v-col cols="12" md="2" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-show="withholding"
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Rate"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-show="withholding"
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Amount"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-show="withholding"
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Withholding Account"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <v-checkbox
                      v-model="deposit"
                      dense
                      hide-details
                      label="Deposit"
                      class="mt-0"
                    ></v-checkbox>
                  </v-col>

                  <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-show="deposit"
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Deposit Account"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-show="deposit"
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Amount"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>

                  <v-col cols="12" md="7"></v-col>
                  <v-col cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                    <vuetify-money
                      v-model="form.code"
                      v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                      v-bind:options="moneyOptionTotal"
                      label="Balance Due"
                      outlined
                      dense
                      class="align-end"
                      hide-details="auto"
                    ></vuetify-money>
                  </v-col>
                </v-row>
              </v-col>
            </v-row>
          </v-container>
        </v-form>
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
  name: 'FormDocument',

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
      logo: '',
      dialog: false,
      checkbox: false,
      deposit: false,
      tax: false,
      withholding: false,
      submitLoad: false,
      form: this.formData,
      itemCategory: [],
      itemUnit: [],
      itemAccounts: [],
      statusProcessing: 'insert',
      valueWhenIsEmpty: '0',
      url: '/api/sales/quote',
      items: ['Item 1', 'Item 2'],
      moneyOptions: {
        suffix: "",
        length: 11,
        precision: 0
      },

      moneyOptionTotal: {
        suffix: "",
        length: 14,
        precision: 2
      },
    }
  },

  mounted() {
    this.getItemCategory()
    this.getItemUnit()
    this.getAccounts()
  },

  methods: {
    addLine() {
      console.log(this.form.details)
      this.form.details.push({
        item_id: null,
        description: null,
        quantity: null,
        unit: null,
        price: null,
        discount_rate: null,
        tax: null,
        total: null,
      })
    },

    removeLine(index, type) {
      this.form.details.splice(index, 1)
    },

    newData(form, defaultItem) {
      this.$refs.dialogForm.openDialog()
      setTimeout(() => {
        this.$refs.childDetails.setDataToDetails([
          {
            item_number: null,
            description: null,
            qty: null,
            unit: null,
          }
        ])
      }, 300)
      this.statusProcessing = 'insert'
      this.form = Object.assign({}, defaultItem)
    },

    editItem(item, url) {
      this.form = Object.assign({}, item)
      this.logo = url + '/files/items/' + this.form.image
      this.statusProcessing = 'update'
      this.$refs.dialogForm.openDialog()
    },

    getItemCategory() {
      this.$axios.get(`/api/master/categories`, {
        params: {
          type: 'Item Category'
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
    },

    getItemUnit() {
      this.$axios.get(`/api/inventory/item-units`, {
        params: {
          type: 'Item Category'
        }
      }).then((res) => {
        this.itemUnit = res.data.data.simple
      })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
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
      const status = this.statusProcessing

      let data = new FormData()
      Object.entries(this.form).forEach(entry => {
        const [key, value] = entry
        data.append(key, value)
      })

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
      let options = {
        headers: {
          'Content-Type': "Multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
        }
      }
      if (method === 'post') {
        this.$axios.post(url, data, options)
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
      } else {
        this.$axios.put(url, data, options)
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
      }
    },
  },
}
</script>
