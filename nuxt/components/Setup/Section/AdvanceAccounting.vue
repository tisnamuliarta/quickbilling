<template>
  <v-row no-gutters>
    <v-col cols="12" md="2" class="pa-1">
      <span class="text-subtitle-1">Accounting</span>
    </v-col>
    <v-col cols="12" md="8" class="pa-1">
      <FormSectionView v-if="companyNameView">
        <template #content>
          <v-row no-gutters @click="companyNameView = false">
            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              First month of fiscal year
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.advanced_first_month_fiscal_year"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              First month income tax year
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.advanced_first_month_incoming_tax"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Accounting Method
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.advanced_accounting_method"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Close the books
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2"
                    v-text="$formatter.formatCheckBox(form.advanced_closed_books)"></span>
            </v-col>
          </v-row>
        </template>
      </FormSectionView>

      <FormSectionEdit ref="sectionEdit" v-else @save="save" @cancel="cancel">
        <template #content>
          <v-row no-gutters>
            <v-col cols="12" md="5" class="pa-2">
              <v-list-item two-line class="pa-0">
                <v-list-item-content>
                  <v-list-item-title>First month of fiscal year</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-select
                label="First month of fiscal year"
                v-model="form.advanced_first_month_fiscal_year"
                :items="itemMonth"
                item-text="text"
                item-value="value"
                filled
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="5" class="pa-2">
              <v-list-item two-line class="pa-0">
                <v-list-item-content>
                  <v-list-item-title>First month income tax year</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-select
                label="First month income tax year"
                v-model="form.advanced_first_month_incoming_tax"
                :items="itemFiscalYear"
                filled
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="5" class="pa-2">
              <v-list-item two-line class="pa-0">
                <v-list-item-content>
                  <v-list-item-title>Accounting Method</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-select
                label="Accounting Method"
                v-model="form.advanced_accounting_method"
                :items="itemAccountingMethod"
                filled
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="5" class="pa-2">
              <v-checkbox
                v-model="form.advanced_closed_books"
                label="Close the books"
                hide-details="auto"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <span class="text-subtitle-2"
                    v-text="$formatter.formatCheckBox(form.advanced_closed_books)"
              ></span>
            </v-col>

            <v-col v-if="form.advanced_closed_books" cols="12" md="5" class="pa-2">
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
                    v-model="form.advanced_closing_date"
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
                  v-model="form.advanced_closing_date"
                  no-title
                  @input="menu = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <span class="text-subtitle-2" v-text="form.advanced_closing_date"></span>
            </v-col>

            <v-col v-if="form.advanced_closing_date" cols="12" md="8" class="pa-2">
              <v-select
                v-model="form.advanced_closing_action"
                label="Action"
                :items="itemCloseBook"
                filled
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col v-if="form.advanced_closing_action === itemCloseBook[1]" cols="12" md="8" class="pa-2">
              <v-text-field
                v-model="form.advanced_closing_password"
                :append-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
                :type="show ? 'text' : 'password'"
                outlined
                dense
                label="Password"
                required
                hide-details="auto"
                @click:append="show = !show"
              ></v-text-field>
            </v-col>

          </v-row>
        </template>
      </FormSectionEdit>
    </v-col>
    <v-col v-if="companyNameView" cols="12" md="1" class="pa-1 text-right">
      <v-btn icon small @click="companyNameView = false">
        <v-icon>mdi-pencil</v-icon>
      </v-btn>
    </v-col>
  </v-row>
</template>

<script>
export default {
  props: {
    form: {
      type: Object,
      default() {
        return {}
      }
    },

    logo: {
      type: String,
      default: ''
    }
  },

  data() {
    return {
      menu: '',
      show: false,
      companyNameView: true,
      itemPaymentTerm: [],
      itemMonth: [
        {text: 'January', value: 1},
        {text: 'February', value: 2},
        {text: 'March', value: 3},
        {text: 'April', value: 4},
        {text: 'May', value: 5},
        {text: 'June', value: 6},
        {text: 'July', value: 7},
        {text: 'August', value: 8},
        {text: 'September', value: 9},
        {text: 'October', value: 10},
        {text: 'November', value: 11},
        {text: 'December', value: 12},
      ],
      itemFiscalYear: ['Same as fiscal year', 'January'],
      itemAccountingMethod: ['Accrual', 'Cash'],
      itemCloseBook: ['Allow changes after viewing a warning', 'Allow changes after viewing a warning and entering password']
    }
  },

  mounted() {
    this.getPaymentTerm()
  },

  methods: {
    getPaymentTerm() {
      this.$axios.get(`/api/financial/payment-terms`)
        .then(res => {
          this.itemPaymentTerm = res.data.data.simple
        })
    },

    save() {
      this.$refs.sectionEdit.save(this.form)
      this.companyNameView = true
    },

    cancel() {
      this.companyNameView = true
    },
  }
}
</script>
