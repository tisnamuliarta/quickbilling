<template>
  <v-form class="pt-0">
    <v-container fluid>
      <v-row v-show="showLoading" no-gutters>
        <v-col cols="12">
          <v-skeleton-loader
            type="list-item-three-line, table-thead, table-tbody, list-item-three-line"
          ></v-skeleton-loader>
        </v-col>
      </v-row>
      <v-row v-show="!showLoading" no-gutters>
        <v-col cols="12">
          <v-row no-gutters>
            <v-col cols="12" md="3" sm="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-select
                v-model="form.contact_id"
                :items="itemContact"
                label="Customer/Vendor"
                item-value="id"
                item-text="name"
                outlined
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-menu
                ref='menu'
                v-model='menu'
                :close-on-content-click='false'
                transition='scale-transition'
                offset-y
                min-width='290px'
              >
                <template #activator='{ on, attrs }'>
                  <v-text-field
                    v-model="form.issued_at"
                    label="Transaction Date"
                    prepend-icon='mdi-calendar'
                    readonly
                    persistent-hint
                    outlined dense hide-details='auto'
                    v-bind='attrs'
                    v-on='on'
                  ></v-text-field>
                </template>

                <v-date-picker
                  v-model="form.issued_at"
                  no-title
                  @input='menu = false'
                >
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-menu
                ref='menu2'
                v-model='menu2'
                :close-on-content-click='false'
                transition='scale-transition'
                offset-y
                min-width='290px'
              >
                <template #activator='{ on, attrs }'>
                  <v-text-field
                    v-show="form.shipping_info"
                    v-model="form.shipping_date"
                    label="Shipping Date"
                    prepend-icon='mdi-calendar'
                    readonly
                    persistent-hint
                    outlined dense hide-details='auto'
                    v-bind='attrs'
                    v-on='on'
                  ></v-text-field>
                </template>

                <v-date-picker
                  v-model="form.shipping_date"
                  no-title
                  @input='menu2 = false'
                >
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-text-field
                v-model="form.document_number"
                readonly
                label="Transaction Number"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
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
                <template v-slot:no-data>
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title>
                        No results matching "<strong>{{ search }}</strong>". Press <kbd>enter</kbd> to create a new one
                      </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </template>
              </v-combobox>
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12">
          <v-row no-gutters>
            <v-col cols="12" md="3" sm="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-textarea
                rows="2"
                v-model="form.contact_address"
                label="Billing Address"
                outlined
                dense
                hide-details="auto"
              ></v-textarea>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
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
                    v-model="form.due_at"
                    label="Due Date"
                    prepend-icon='mdi-calendar'
                    readonly
                    persistent-hint
                    outlined dense hide-details='auto'
                    v-bind='attrs'
                    v-on='on'
                  ></v-text-field>
                </template>

                <v-date-picker
                  v-model="form.due_at"
                  no-title
                  @input='menu3 = false'
                >
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-text-field
                v-show="form.shipping_info"
                v-model="form.shipping_via"
                label="Ship Via"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-text-field
                v-model="form.reference_no"
                label="Reference No"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3" sm="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-textarea
                v-show="form.shipping_info"
                v-model="form.shipping_address"
                rows="2"
                label="Shipping Address"
                outlined
                dense
                hide-details="auto"
              ></v-textarea>
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12">
          <v-row no-gutters>
            <v-col cols="12" md="3" sm="6" class="pr-1 pl-1 pb-1">
              <v-checkbox
                v-model="form.shipping_info"
                dense
                hide-details
                label="Shipping Info"
                class="mt-0"
              ></v-checkbox>
            </v-col>

            <v-col cols="12" md="2" sm="6" class="pr-1 pl-1 pb-1 pt-1 mt-1">
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

            <v-col cols="12" md="2" sm="6" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-text-field
                v-show="form.shipping_info"
                v-model="form.tracking_code"
                label="Tracking No"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2" sm="6" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-select
                v-model="form.warehouse_id"
                :items="itemWarehouse"
                item-text="name"
                item-value="id"
                label="Warehouse"
                outlined
                dense
                hide-details="auto"
              ></v-select>
            </v-col>
          </v-row>
        </v-col>

        <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
          <v-card>
            <div class="scroll-container-min">
              <LazyDocumentTableDetail
                ref="childDetails"
                @calcTotal="calcTotal"
              ></LazyDocumentTableDetail>
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
                <v-icon>mdi-plus</v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>

        <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
          <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-textarea
              v-model="form.footer"
              rows="2"
              label="Message"
              outlined
              dense
              hide-details="auto"
            ></v-textarea>
          </v-col>

          <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-textarea
              v-model="form.notes"
              rows="2"
              label="Memo"
              outlined
              dense
              hide-details="auto"
            ></v-textarea>
          </v-col>

          <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <DocumentFieldUpload
              :form-data="form"
              form-type="document"
              @eventGetFiles="eventGetFiles"
            ></DocumentFieldUpload>
          </v-col>

          <v-col v-if="itemFiles.length > 0" cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-list dense>
              <v-subheader>Files</v-subheader>
              <v-list-item-group
                v-model="selectedItem"
                color="primary"
              >
                <v-list-item
                  v-for="(item, i) in itemFiles"
                  :key="i"
                >
                  <v-list-item-content>
                    <v-list-item-title>
                      <a :href="item.directory" target="_blank">{{ item.filename }}</a>
                    </v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-col>
        </v-col>

        <v-spacer class="hidden-sm-and-down"></v-spacer>

        <v-col cols="12" md="6" lg="5" xl="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
          <v-row no-gutters>

            <v-col cols="12" md="8"></v-col>
            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <vuetify-money
                v-model="form.sub_total"
                v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                v-bind:options="moneyOptionTotal"
                readonly
                label="Sub Total"
                outlined
                dense
                reverse
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>

            <v-col cols="12" md="8"></v-col>

            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <vuetify-money
                v-model="form.discount_per_line"
                v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                v-bind:options="moneyOptionTotal"
                readonly
                label="Discount Per Lines"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>

            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-select
                v-model="form.discount_type"
                :items="['Amount', 'Percent']"
                label="Discount Type"
                outlined
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <vuetify-money
                v-model="form.discount_rate"
                v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                v-bind:options="moneyOptionTotalDiscount"
                label="Discount Rate"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>

            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <vuetify-money
                v-model="form.discount_amount"
                v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                v-bind:options="moneyOptionTotal"
                readonly
                label="Discount Amount"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>

            <v-col cols="12">
              <v-row
                v-if="form.tax_details.length > 0"
                v-for="(item, index) in form.tax_details"
                :key="index"
                no-gutters
              >
                <v-col cols="12" md="8"></v-col>
                <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <vuetify-money
                    v-model="item.tax"
                    v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                    v-bind:options="moneyOptionTotal"
                    readonly
                    :label="item.name"
                    outlined
                    dense
                    class="text-money"
                    hide-details="auto"
                  ></vuetify-money>
                </v-col>
              </v-row>
            </v-col>

            <v-col v-show="form.shipping_info" cols="12" md="8"></v-col>
            <v-col v-show="form.shipping_info" cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <vuetify-money
                v-model="form.shipping_fee"
                v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                v-bind:options="moneyOptionTotal"
                label="Shipping Fee"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>

            <v-col cols="12" md="8"></v-col>
            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <vuetify-money
                v-model="form.amount"
                v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                v-bind:options="moneyOptionTotal"
                readonly
                label="Total"
                outlined
                dense
                class="text-money"
                hide-details="auto"
              ></vuetify-money>
            </v-col>

            <v-col v-if="checkDocument()" cols="12">
              <v-row no-gutters>
                <v-col cols="12" md="2" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <v-checkbox
                    v-model="form.withholding_info"
                    dense
                    hide-details
                    label="Withholding"
                    class="mt-0"
                  ></v-checkbox>
                </v-col>

                <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
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

                <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <vuetify-money
                    v-show="form.withholding_info"
                    v-model="form.withholding_rate"
                    v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                    v-bind:options="moneyOptionTotal"
                    label="Rate"
                    outlined
                    dense
                    class="text-money"
                    hide-details="auto"
                  ></vuetify-money>
                </v-col>

                <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <vuetify-money
                    v-show="form.withholding_info"
                    v-model="form.withholding_amount"
                    v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                    v-bind:options="moneyOptionTotal"
                    readonly
                    label="Amount"
                    outlined
                    dense
                    class="text-money"
                    hide-details="auto"
                  ></vuetify-money>
                </v-col>

                <v-col v-show="form.withholding_info" cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
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
            </v-col>

            <v-col v-if="checkDocument()" cols="12">
              <v-row no-gutters>
                <v-col cols="12" md="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <v-checkbox
                    v-model="form.deposit_info"
                    dense
                    hide-details
                    label="Deposit"
                    class="mt-0"
                  ></v-checkbox>
                </v-col>

                <v-col cols="12" md="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <v-select
                    v-show="form.deposit_info"
                    v-model="form.deposit_account_id"
                    :items="itemAccounts"
                    item-text="name"
                    item-value="id"
                    label="Deposit Account"
                    outlined
                    dense
                    hide-details="auto"
                  ></v-select>
                </v-col>

                <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <vuetify-money
                    v-show="form.deposit_info"
                    v-model="form.deposit_amount"
                    v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                    v-bind:options="moneyOptionTotal"
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
              <v-row no-gutters>
                <v-spacer></v-spacer>
                <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                  <vuetify-money
                    v-model="form.balance_due"
                    v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                    v-bind:options="moneyOptionTotal"
                    readonly
                    label="Balance Due"
                    outlined
                    dense
                    class="text-money"
                    hide-details="auto"
                  ></vuetify-money>
                </v-col>
              </v-row>
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-container>

    <LazyDocumentDialogSendEmail ref="dialogSendEmail" ></LazyDocumentDialogSendEmail>
  </v-form>
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
    url: {
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
        locale: "en",
        prefix: "",
        length: 11,
        precision: 0
      },

      moneyOptionTotal: {
        locale: "en",
        prefix: "",
        length: 14,
        precision: 2
      },

      moneyOptionTotalDiscount: {
        locale: "en",
        prefix: "",
        length: 14,
        precision: 0
      },
    }
  },

  computed: {
    depositAmount() {
      return this.form.deposit_amount;
    },
    discountRate() {
      return this.form.discount_rate;
    },
    discountType() {
      return this.form.discount_type;
    },
    withholdingType() {
      return this.form.withholding_type;
    },
    withholdingRate() {
      return this.form.withholding_rate;
    },
    shippingFee() {
      return this.form.shipping_fee;
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
    calcTotal(data) {
      this.form.discount_per_line = data.discountAmount
      this.form.sub_total = data.subTotal
      this.form.tax_details = this.reduceArrayTax(data.taxDetail)
      this.taxDetails = this.reduceArrayTax(data.taxDetail)
      this.form.amount = data.amount + this.tempTotalTax
      this.form.balance_due = this.form.amount
      this.subTotalMinDiscount = parseFloat(this.form.sub_total) - parseFloat(this.form.discount_per_line)
      this.taxAmount = this.tempTotalTax

      if (this.form.sub_total === 0) {
        this.form.discount_rate = 0
        this.form.discount_amount = 0
      }

      this.changeCalculation()
    },

    changeCalculation() {
      // calculate discount
      if (this.form.discount_type === 'Percent') {
        if (this.form.discount_rate > 0) {
          this.form.discount_amount = (this.form.discount_rate / 100) * this.subTotalMinDiscount
          this.taxDiscount = (this.form.discount_rate / 100) * this.tempTotalTax
        }
      } else {
        this.form.discount_amount = parseFloat(this.form.discount_rate)
        if (this.tempTotalTax > 0) {
          this.taxDiscount = this.tempTotalTax - this.form.discount_amount
        }
      }
      this.taxAmount = parseFloat(this.tempTotalTax) - parseFloat(this.taxDiscount)
      this.taxAmount = (this.taxAmount === undefined) ? 0 : this.taxAmount

      // calculate tax details
      if (this.form.discount_rate > 0) {
        this.form.tax_details = this.reduceArrayTaxAfterDiscount(this.taxDetails)
      }
      // calculate total amount
      this.form.amount = this.form.sub_total - this.form.discount_per_line - this.form.discount_amount + this.taxAmount

      // calculate amount before tax for tax withholding
      this.amountBeforeTax = this.form.amount - this.taxAmount

      // calculate tax withholding
      if (this.form.withholding_type === 'Percent') {
        if (this.form.withholding_rate > 0) {
          this.form.withholding_amount = (this.form.withholding_rate / 100) * this.amountBeforeTax
        }
      } else {
        this.form.withholding_amount = parseFloat(this.form.withholding_rate)
      }

      this.form.balance_due = this.form.amount - this.form.deposit_amount - this.form.withholding_amount - parseFloat(this.form.shipping_fee)
    },

    reduceArrayTaxAfterDiscount(tax_details) {
      const result = [];
      const vm = this
      tax_details.reduce(function (res, value) {
        if (!res[value.name]) {
          res[value.name] = {name: value.name, tax: 0};
          result.push(res[value.name])
        }
        if (vm.form.discount_rate > 0) {
          let taxDiscountValue = 0
          if (vm.form.discount_type === 'Percent') {
            taxDiscountValue = (vm.form.discount_rate / 100) * value.tax
          } else {
            taxDiscountValue = vm.form.discount_rate
          }
          res[value.name].tax = value.tax - taxDiscountValue;
        }
        return res;
      }, {});

      return result
    },

    reduceArrayTax(tax_details) {
      const result = [];
      const vm = this
      let totalTax = 0;
      tax_details.forEach(function (item, index) {
        totalTax += parseFloat(item.tax)
      })
      vm.tempTotalTax = totalTax

      tax_details.reduce(function (res, value) {
        if (!res[value.name]) {
          res[value.name] = {name: value.name, tax: 0};
          result.push(res[value.name])
        }
        res[value.name].tax += value.tax;

        return res;
      }, {});

      return result
    },

    showLoad(value) {
      this.showLoading = value
    },

    setData(form) {
      this.$refs.childDetails.setDataToDetails([
        {
          item_number: null,
          description: null,
          qty: null,
          unit: null,
        }
      ], form)
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
      //this.$refs.dialogSendEmail.openEmailDialog()
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

    getContact() {
      this.$axios.get(`/api/inventory/contacts`, {
        params: {
          type: "All"
        }
      })
        .then((res) => {
          this.itemContact = res.data.data.auto_complete
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

    getTax() {
      this.$axios.get(`/api/financial/taxes`, {
        params: {
          type: "All"
        }
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
      this.$axios.get(`/api/financial/payment-terms/` + this.form.payment_term_id)
        .then(res => {

        })
    },

    checkDocument() {
      const routeType = this.$route.query.type
      switch (routeType) {
        case 'SQ':
        case 'PQ':
          return false;
        default:
          return true
      }
    },

    returnData() {
      const vm = this
      const details = {}
      const clearData = vm.$refs.childDetails.getAddData()
      clearData.forEach(function (item, key) {
        if (!vm.$refs.childDetails.checkIfEmptyRow(key)) details[key] = item
      })

      this.form.items = details
      return this.form
    },
  },
}
</script>
