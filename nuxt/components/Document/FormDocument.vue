<template>
  <v-row dense>
    <v-col cols="12" md="9" sm="8">
      <v-row dense>
        <v-col cols="12" md="4" sm="12">
          <v-autocomplete
            v-model="form.contact_id"
            :items="itemContact"
            label="Customer/Vendor"
            return-object
            item-value="id"
            item-text="name"
            outlined
            dense
            hide-details="auto"
            @change="changeContact"
          ></v-autocomplete>
        </v-col>

        <v-col cols="12" md="4">
          <v-combobox
            v-model="form.tags"
            :items="itemTag"
            :search-input.sync="search"
            hide-selected
            label="Customer Email"
            hide-details
            dense
            multiple
            persistent-hint
            small-chips
            outlined
          >
            <template #no-data>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>
                    No results matching "<strong>{{ search }}</strong
                    >". Press <kbd>enter</kbd> to create a new one
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </template>
          </v-combobox>
        </v-col>

        <v-col cols="12" md="2"></v-col>

        <v-col cols="12" md="4">
          <v-textarea
            v-model="form.contact_address"
            rows="3"
            label="Billing Address"
            outlined
            dense
            hide-details="auto"
          ></v-textarea>
        </v-col>

        <v-col cols="12" md="2" sm="6">
          <v-select
            v-model="form.payment_term_id"
            :items="itemPaymentTerm"
            item-value="id"
            item-text="name"
            label="Payment Term"
            outlined
            dense
            hide-details="auto"
            @change="changePaymentTerm"
          ></v-select>
        </v-col>

        <v-col cols="12" md="2" sm="4">
          <v-menu
            ref="menu"
            v-model="menu"
            :close-on-content-click="false"
            transition="scale-transition"
            offset-y
            min-width="290px"
          >
            <template #activator="{ on, attrs }">
              <v-text-field
                v-model="form.issued_at"
                label="Transaction Date"
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
              v-model="form.issued_at"
              no-title
              @input="menu = false"
            >
            </v-date-picker>
          </v-menu>
        </v-col>

        <v-col cols="12" md="2" sm="4">
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
                v-model="form.due_at"
                label="Due Date"
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
              v-model="form.due_at"
              no-title
              @input="menu3 = false"
            >
            </v-date-picker>
          </v-menu>
        </v-col>

        <v-col cols="12" md="8">
          <v-combobox
            v-model="form.tags"
            :items="itemTag"
            :search-input.sync="search"
            hide-selected
            label="Tags"
            hide-details
            dense
            multiple
            persistent-hint
            small-chips
            outlined
          >
            <template #no-data>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>
                    No results matching "<strong>{{ search }}</strong
                    >". Press <kbd>enter</kbd> to create a new one
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </template>
          </v-combobox>
        </v-col>
      </v-row>
    </v-col>

    <v-col cols="12" md="3" sm="4" class="text-right">
      <v-row dense>
        <v-col cols="12">
          <p class="mb-0">Amount Due</p>
          <span class="text-right font-weight-bold text-h4">
            {{
              isNaN(form.balance_due) ? 0 : $formatter.formatPrice(form.balance_due)
            }}
          </span>
        </v-col>
        <v-col cols="12">
          <v-text-field
            v-model="form.document_number"
            readonly
            label="Transaction Number"
            outlined
            dense
            hide-details="auto"
          ></v-text-field>
        </v-col>
      </v-row>
    </v-col>

    <v-col cols="12">
      <v-card flat>
        <div class="scroll-container-min">
          <LazyDocumentTableDetail
            ref="childDetails"
            @calcTotal="calcTotal"
          ></LazyDocumentTableDetail>
        </div>
        <v-card-actions>
          <v-btn small depressed outlined @click="$refs.childDetails.addLine()">
            Add Line
            <v-icon>mdi-plus</v-icon>
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>

    <v-col cols="12" md="4" lg="4">
      <v-col cols="12" md="12">
        <v-textarea
          v-model="form.footer"
          rows="2"
          label="Message"
          outlined
          dense
          hide-details="auto"
        ></v-textarea>
      </v-col>

      <v-col cols="12" md="12">
        <v-textarea
          v-model="form.notes"
          rows="2"
          label="Memo"
          outlined
          dense
          hide-details="auto"
        ></v-textarea>
      </v-col>

      <v-col cols="12" md="12">
        <DocumentFieldUpload
          ref="uploadField"
          :form-data="form"
          form-type="document"
          @eventGetFiles="eventGetFiles"
        ></DocumentFieldUpload>
      </v-col>
    </v-col>

    <v-spacer />

    <v-col cols="12" md="6" lg="5">
      <v-row dense>
        <v-col cols="12">
          <v-row dense>
            <v-spacer />
            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">Subtotal</span>
            </v-col>
            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">
                {{
                  isNaN(form.sub_total) ? 0 : $formatter.formatPrice(form.sub_total)
                }}
              </span>
            </v-col>
          </v-row>
        </v-col>

        <!-- <v-col cols="12">
          <v-row dense>
            <v-spacer />
            <v-col cols="12" md="4">
              <vuetify-money
                v-model="form.discount_per_line"
                :value-when-is-empty="valueWhenIsEmpty"
                :options="moneyOptionTotal"
                readonly
                label="Discount Per Lines"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>
          </v-row>
        </v-col> -->

        <v-col cols="12">
          <v-row dense class="align-right">
            <v-spacer />
            <v-col cols="12" md="4" class="pa-1">
              <v-select
                v-model="form.discount_type"
                :items="['Value', 'Percent']"
                label="Discount Type"
                outlined
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="3" class="pa-1">
              <vuetify-money
                v-model="form.discount_rate"
                :value-when-is-empty="valueWhenIsEmpty"
                :options="moneyOptionTotalDiscount"
                label="Discount Rate"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>

            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">
                {{
                  isNaN(form.discount_amount) ? 0 : $formatter.formatPrice(form.discount_amount)
                }}
              </span>
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12">
          <v-row
            v-for="(item, index) in form.tax_details"
            v-if="form.tax_details.length > 0"
            :key="index"
            dense
          >
            <v-spacer />
            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">{{ item.name }}</span>
            </v-col>
            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">
                {{
                  isNaN(item.amount) ? 0 : $formatter.formatPrice(item.amount)
                }}
              </span>
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12">
          <v-row dense>
            <v-spacer />
            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">Shipping</span>
            </v-col>
            <v-col cols="12" md="4" class="pa-1">
              <vuetify-money
                v-model="form.shipping_fee"
                :value-when-is-empty="valueWhenIsEmpty"
                :options="moneyOptionTotal"
                label="Shipping Fee"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12">
          <v-row dense>
            <v-spacer />
            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">Total</span>
            </v-col>
            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">
                {{
                  isNaN(form.amount) ? 0 : $formatter.formatPrice(form.amount)
                }}
              </span>
            </v-col>
          </v-row>
        </v-col>

        <!-- <v-col cols="12">
          <v-row dense>
            <v-col cols="12" md="2">
              <v-checkbox
                v-model="form.withholding_info"
                dense
                hide-details
                label="Withholding"
                class="mt-0"
              ></v-checkbox>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-show="form.withholding_info"
                v-model="form.withholding_type"
                :items="['Amount', 'Percent']"
                label="Type"
                outlined
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <vuetify-money
                v-show="form.withholding_info"
                v-model="form.withholding_rate"
                :value-when-is-empty="valueWhenIsEmpty"
                :options="moneyOptionTotal"
                label="Rate"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>

            <v-col cols="12" md="4">
              <vuetify-money
                v-show="form.withholding_info"
                v-model="form.withholding_amount"
                :value-when-is-empty="valueWhenIsEmpty"
                :options="moneyOptionTotal"
                readonly
                label="Amount"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>

            <v-col v-show="form.withholding_info" cols="12" md="12">
              <v-select
                v-model="form.withholding_account_id"
                :items="itemAccounts"
                item-value="id"
                item-text="name"
                label="Withholding Account"
                outlined
                dense
                hide-details="auto"
              ></v-select>
            </v-col>
          </v-row>
        </v-col> -->

        <v-col cols="12">
          <v-row dense>
            <v-spacer />
            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">Deposit</span>
            </v-col>
            <v-col cols="12" md="4" class="pa-1">
              <vuetify-money
                v-model="form.deposit_amount"
                :value-when-is-empty="valueWhenIsEmpty"
                :options="moneyOptionTotal"
                label="Amount"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12">
          <v-row dense>
            <v-spacer />
            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">Balance Due</span>
            </v-col>
            <v-col cols="12" md="4" class="text-right pa-1">
              <span class="font-weight-bold subtitle-1">
                {{
                  isNaN(form.balance_due) ? 0 : $formatter.formatPrice(form.balance_due)
                }}
              </span>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'FormDocument',

  props: {
    formType: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      menu: '',
      menu2: '',
      menu3: '',
      menu4: '',
      logo: '',
      search: null,
      selectedItem: 1,
      dialog: false,
      checkbox: false,
      deposit: false,
      tax: true,
      withholding: false,
      submitLoad: false,
      showLoading: false,
      form: {},
      itemCategory: [],
      itemUnit: [],
      itemContact: [],
      itemAccounts: [],
      itemTag: [],
      itemPaymentTerm: [],
      itemWarehouse: [],
      itemFiles: [],
      taxDetails: [],
      statusProcessing: 'insert',
      valueWhenIsEmpty: '0',
      tempTotalTax: 0,
      subTotalMinDiscount: 0,
      taxAmount: 0,
      taxDiscount: 0,
      amountBeforeTax: 0,
      withholdingAmount: 0,
      discountAmount: 0,
      moneyOptions: {
        locale: 'en',
        prefix: '',
        length: 11,
        precision: 0,
      },

      moneyOptionTotal: {
        locale: 'en',
        prefix: '',
        length: 14,
        precision: 2,
      },

      moneyOptionTotalDiscount: {
        locale: 'en',
        prefix: '',
        length: 14,
        precision: 0,
      },
    }
  },

  computed: {
    depositAmount() {
      return this.form.deposit_amount
    },
    discountRate() {
      return this.form.discount_rate
    },
    discountType() {
      return this.form.discount_type
    },
    withholdingType() {
      return this.form.withholding_type
    },
    withholdingRate() {
      return this.form.withholding_rate
    },
    shippingFee() {
      return this.form.shipping_fee
    },
  },

  watch: {
    shippingFee: {
      handler() {
        this.changeCalculation()
      },
      deep: true,
    },
    depositAmount: {
      handler() {
        this.changeCalculation()
      },
      deep: true,
    },
    withholdingType: {
      handler() {
        this.changeCalculation()
      },
      deep: true,
    },
    discountType: {
      handler() {
        this.changeCalculation()
      },
      deep: true,
    },
    discountRate: {
      handler() {
        this.changeCalculation()
      },
      deep: true,
    },
    withholdingRate: {
      handler() {
        this.changeCalculation()
      },
      deep: true,
    },
  },

  mounted() {
    this.getItemCategory()
    this.getItemUnit()
    this.getAccounts()
    this.getContact()
    this.getPaymentTerms()
    this.getTax()
  },

  methods: {
    checkDisable() {
      return this.form.status === 'closed' || this.form.status === 'cancel'
    },

    calcTotal(data) {
      this.form.discount_per_line = data.discountAmount
      this.form.sub_total = data.subTotal
      this.form.tax_details = this.reduceArrayTax(data.taxDetail)
      this.taxDetails = data.taxDetail
      this.form.amount = data.amount + this.tempTotalTax
      this.form.balance_due = this.form.amount
      this.subTotalMinDiscount =
        parseFloat(this.form.sub_total) -
        parseFloat(this.form.discount_per_line)
      // this.taxAmount = this.tempTotalTax

      if (this.form.sub_total === 0) {
        this.form.discount_rate = 0
        this.form.discount_amount = 0
      }

      this.changeCalculation(data)
    },

    changeCalculation(data) {
      // this.taxDetails = (data) ? data.taxDetail : []
      // calculate discount
      if (this.taxDetails.length > 0) {
        this.form.tax_details = this.reduceArrayTax(this.taxDetails)
      }
      this.form.discount_amount = 0
      if (this.form.discount_type === 'Percent') {
        if (this.form.discount_rate > 0) {
          this.subTotalMinDiscount =
            parseFloat(this.form.sub_total) -
            parseFloat(this.form.discount_per_line)
          this.form.discount_amount =
            (this.form.discount_rate / 100) * this.subTotalMinDiscount
          this.taxDiscount = (this.form.discount_rate / 100) * this.tempTotalTax
        }
      } else {
        this.form.discount_amount = parseFloat(this.form.discount_rate)
        if (this.tempTotalTax > 0) {
          this.taxDiscount = this.tempTotalTax - this.form.discount_amount
        }
      }

      this.taxAmount =
        parseFloat(this.tempTotalTax) - parseFloat(this.taxDiscount)

      this.taxAmount = this.taxAmount === undefined ? 0 : this.taxAmount

      // calculate tax details
      // if (this.form.discount_rate > 0) {
      // }
      if (
        this.taxDetails.length > 0 &&
        parseFloat(this.form.discount_rate) > 0
      ) {
        this.form.tax_details = this.reduceArrayTaxAfterDiscount(
          this.taxDetails
        )
      }

      // calculate total amount
      // console.log(this.form.sub_total)
      // console.log(this.form.discount_per_line)
      // console.log(this.form.discount_amount)
      this.form.amount =
        parseFloat(this.form.sub_total) -
        parseFloat(this.form.discount_per_line) -
        parseFloat(this.form.discount_amount) +
        parseFloat(this.taxAmount)

      // calculate amount before tax for tax withholding
      this.amountBeforeTax = this.form.amount - this.taxAmount

      // calculate tax withholding
      if (this.form.withholding_type === 'Percent') {
        if (this.form.withholding_rate > 0) {
          this.form.withholding_amount =
            (this.form.withholding_rate / 100) * this.amountBeforeTax
        }
      } else {
        this.form.withholding_amount = parseFloat(this.form.withholding_rate)
      }

      this.form.balance_due =
        this.form.amount -
        this.form.deposit_amount -
        this.form.withholding_amount -
        parseFloat(this.form.shipping_fee)
    },

    reduceArrayTaxAfterDiscount(tax_details) {
      const result = []
      const vm = this
      // console.log(tax_details)
      tax_details.reduce(function (res, value) {
        if (!res[value.name]) {
          res[value.name] = { name: value.name, amount: 0 }
          result.push(res[value.name])
        }

        if (parseFloat(vm.form.discount_rate) > 0) {
          let taxDiscountValue = 0
          if (vm.form.discount_type === 'Percent') {
            taxDiscountValue =
              (parseFloat(vm.form.discount_rate) / 100) *
              parseFloat(value.amount)
          } else {
            taxDiscountValue = parseFloat(vm.form.discount_rate)
          }
          res[value.name].amount =
            parseFloat(value.amount) - parseFloat(taxDiscountValue)
        }
        return res
      }, {})

      return result
    },

    reduceArrayTax(tax_details) {
      const result = []
      const vm = this
      let totalTax = 0
      tax_details.forEach(function (item, index) {
        totalTax += parseFloat(item.amount)
      })
      vm.tempTotalTax = totalTax

      tax_details.reduce(function (res, value) {
        if (!res[value.name]) {
          res[value.name] = { name: value.name, amount: 0 }
          result.push(res[value.name])
        }
        res[value.name].amount += value.amount

        return res
      }, {})

      return result
    },

    showLoad(value) {
      this.showLoading = value
    },

    setData(form) {
      this.showLoading = true
      setTimeout(() => {
        this.$refs.childDetails.setDataToDetails(
          [
            {
              item_number: null,
              description: null,
              qty: null,
              unit: null,
            },
            {
              item_number: null,
              description: null,
              qty: null,
              unit: null,
            },
          ],
          form
        )
      }, 500)

      this.form = Object.assign({}, form)
      this.moneyOptionTotal.prefix = this.form.default_currency_symbol
      this.moneyOptionTotalDiscount.prefix = this.form.default_currency_symbol
      this.moneyOptions.prefix = this.form.default_currency_symbol
      this.statusProcessing = 'insert'
    },

    eventGetFiles(data) {
      this.itemFiles = data.row
    },

    getItemCategory() {
      // this.$refs.dialogSendEmail.openEmailDialog()
      this.$axios
        .get(`/api/master/categories`, {
          params: {
            type: 'Item Category',
          },
        })
        .then((res) => {
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
      this.$axios
        .get(`/api/inventory/item-units`, {
          params: {
            type: 'Item Category',
          },
        })
        .then((res) => {
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
      this.$axios
        .get(`/api/financial/accounts`, {
          params: {
            type: 'All',
          },
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

    getContact() {
      const vm = this
      this.$axios
        .get(`/api/bp/contacts`, {
          params: {
            type: vm.$route.query.type,
          },
        })
        .then((res) => {
          this.itemContact = res.data.data.rows
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
      this.$axios
        .get(`/api/financial/payment-terms`, {
          params: {
            type: 'All',
          },
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

    getTax() {
      // this.$refs.uploadField.getFiles()

      this.$axios
        .get(`/api/financial/taxes`, {
          params: {
            type: 'All',
          },
        })
        .then((res) => {
          this.$auth.$storage.setLocalStorage('tax', res.data.data.simple)
          this.$auth.$storage.setLocalStorage('tax_row', res.data.data.rows)
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    changePaymentTerm() {
      this.itemPaymentTerm
      const issueAt = this.form.issue_at
      const due_at = this.form.due_at
      this.$axios
        .get(`/api/financial/payment-terms/` + this.form.payment_term_id)
        .then((res) => {})
    },

    changeContact() {
      const contact = this.form.contact_id
      this.form.contact_id = contact.id
      this.form.contact_address = this.form.contact_address
        ? this.form.contact_address
        : contact.address
      this.form.shipping_address = this.form.shipping_address
        ? this.form.shipping_address
        : contact.shipping_address
    },

    checkDocument() {
      const routeType = this.$route.query.type
      switch (routeType) {
        case 'SQ':
        case 'PQ':
          return false
        default:
          return true
      }
    },

    returnData(document) {
      const vm = this
      const details = {}
      const clearData = vm.$refs.childDetails.getAddData(document)
      clearData.forEach(function (item, key) {
        if (!vm.$refs.childDetails.checkIfEmptyRow(key)) details[key] = item
      })

      this.form.items = details
      return this.form
    },

    changeValue(key, value) {
      this.form[key] = value
    },
  },
}
</script>
