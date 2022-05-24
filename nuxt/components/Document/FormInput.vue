<template>
  <v-form class="pt-0">
    <v-container fluid>
      <v-row no-gutters>
        <v-col cols="12">
          <v-row no-gutters>
            <v-col cols="12" md="3" sm="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-autocomplete
                v-model="form.contact_id"
                :items="itemContact"
                :disabled="checkDisable()"
                @change="changeContact"
                label="Customer/Vendor"
                return-object
                item-value="id"
                item-text="name"
                outlined
                dense
                clearable
                hide-details="auto"
              ></v-autocomplete>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
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
                  :disabled="checkDisable()"
                  no-title
                  @input="menu = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-menu
                ref="menu2"
                v-model="menu2"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template #activator="{ on, attrs }">
                  <v-text-field
                    v-show="form.shipping_info"
                    v-model="form.shipping_date"
                    label="Shipping Date"
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
                  v-model="form.shipping_date"
                  :disabled="checkDisable()"
                  no-title
                  @input="menu2 = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-text-field
                v-model="form.document_number"
                :disabled="checkDisable()"
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
                :disabled="checkDisable()"
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

        <v-col cols="12">
          <v-row no-gutters>
            <v-col cols="12" md="3" sm="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-text-field
                v-model="form.status"
                :disabled="checkDisable()"
                readonly
                label="Status"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
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
                  :disabled="checkDisable()"
                  no-title
                  @input="menu3 = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-text-field
                v-show="form.shipping_info"
                v-model="form.shipping_via"
                :disabled="checkDisable()"
                label="Ship Via"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2" sm="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-text-field
                v-model="form.reference_no"
                :disabled="checkDisable()"
                label="Reference No"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3" sm="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-select
                v-model="form.warehouse_id"
                :items="itemWarehouse"
                :disabled="checkDisable()"
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

        <v-col cols="12">
          <v-row no-gutters>
            <v-col cols="12" md="3" sm="6" class="pr-1 pl-1 pb-1"></v-col>

            <v-col cols="12" md="2" sm="6" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-select
                v-model="form.payment_term_id"
                :items="itemPaymentTerm"
                :disabled="checkDisable()"
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
                :disabled="checkDisable()"
                label="Tracking No"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2" sm="6" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-checkbox
                v-model="form.shipping_info"
                :disabled="checkDisable()"
                dense
                hide-details
                label="Shipping Info"
                class="mt-0"
              ></v-checkbox>
            </v-col>

            <v-col cols="12" md="3" sm="6" class="pr-1 pl-1 pb-1">
              <v-checkbox
                v-model="form.price_include_tax"
                :disabled="checkDisable()"
                dense
                hide-details
                label="Price Include Tax"
                class="mt-0"
              ></v-checkbox>
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
            <!-- <div> -->
              <!-- <LazyFormAgGrid ref="documentGrid"></LazyFormAgGrid> -->
              <!-- <LazyFormDxDataGrid ref="docGrid"></LazyFormDxDataGrid> -->
            <!-- </div> -->
            <v-card-actions>
              <v-btn
                color="blue darken-1"
                class="white--text"
                small
                depressed
                :disabled="checkDisable()"
                @click="$refs.documentGrid.addItems()"
              >
                Add Line
                <v-icon>mdi-plus</v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-col>

        <v-col cols="12" md="4" lg="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
          <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-textarea
              v-model="form.footer"
              :disabled="checkDisable()"
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
              :disabled="checkDisable()"
              rows="2"
              label="Memo"
              outlined
              dense
              hide-details="auto"
            ></v-textarea>
          </v-col>

          <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <DocumentFieldUpload
              v-if="!checkDisable()"
              ref="uploadField"
              :form-data="form"
              form-type="document"
              @eventGetFiles="eventGetFiles"
            ></DocumentFieldUpload>
          </v-col>
        </v-col>

        <v-col cols="12" md="2" lg="3" class="pr-1 pl-1 pb-1 pt-1 mt-1">
          <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-textarea
              rows="2"
              v-model="form.contact_address"
              :disabled="checkDisable()"
              label="Billing Address"
              outlined
              dense
              hide-details="auto"
            ></v-textarea>
          </v-col>
          <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-textarea
              v-show="form.shipping_info"
              v-model="form.shipping_address"
              :disabled="checkDisable()"
              rows="2"
              label="Shipping Address"
              outlined
              dense
              hide-details="auto"
            ></v-textarea>
          </v-col>

          <v-col
            v-if="itemFiles.length > 0"
            cols="12"
            md="12"
            class="pr-1 pl-1 pb-1 pt-1 mt-1"
          >
            <v-list dense>
              <v-subheader>Files</v-subheader>
              <v-list-item-group v-model="selectedItem" color="primary">
                <v-list-item v-for="(item, i) in itemFiles" :key="i">
                  <v-list-item-content>
                    <v-list-item-title>
                      <a :href="item.directory" target="_blank">{{
                          item.filename
                        }}</a>
                    </v-list-item-title>
                  </v-list-item-content>
                  <v-list-item-action>
                    <v-btn small dark icon>
                      <v-icon
                        color="red"
                        @click="$refs.uploadField.deleteFile(item)"
                      >mdi-delete
                      </v-icon
                      >
                    </v-btn>
                  </v-list-item-action>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-col>
        </v-col>

        <v-col cols="12" md="6" lg="5" class="pr-1 pl-1 pb-1 pt-1 mt-1">
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
                :disabled="checkDisable()"
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
                    v-model="item.amount"
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
            <v-col
              v-show="form.shipping_info"
              cols="12"
              md="4"
              class="pr-1 pl-1 pb-1 pt-1 mt-1"
            >
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
                    :disabled="checkDisable()"
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
                    :disabled="checkDisable()"
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

                <v-col
                  v-show="form.withholding_info"
                  cols="12"
                  md="12"
                  class="pr-1 pl-1 pb-1 pt-1 mt-1"
                >
                  <v-select
                    v-model="form.withholding_account_id"
                    :disabled="checkDisable()"
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
                    :disabled="checkDisable()"
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
                    :disabled="checkDisable()"
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
                    :disabled="checkDisable()"
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
          res[value.name] = {name: value.name, amount: 0}
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
          res[value.name] = {name: value.name, amount: 0}
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
      //this.$refs.dialogSendEmail.openEmailDialog()
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
      this.$refs.uploadField.getFiles()

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
        .then((res) => {
        })
    },

    changeContact() {
      let contact = this.form.contact_id
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
