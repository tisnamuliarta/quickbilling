<template>
  <v-row no-gutters>
    <v-col cols="12" md="2" class="pa-1">
      <span class="text-subtitle-1">Statements</span>
    </v-col>
    <v-col cols="12" md="8" class="pa-1">
      <FormSectionView v-if="companyNameView">
        <template #content>
          <v-row no-gutters @click="companyNameView = false">
            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Transaction
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.sales_statement_details"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Show aging table at bottom of statement
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_statement_aging)"></span>
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
                  <v-list-item-title>Transaction</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-select
                label="Email Option for sales form"
                v-model="form.sales_statement_details"
                :items="itemDeliveryMethod"
                filled
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="5" class="pa-2">
              <v-checkbox
                v-model="form.sales_statement_aging"
                label="Show aging table at bottom of statement"
                hide-details="auto"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_statement_aging)"></span>
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
      companyNameView: true,
      itemPaymentTerm: [],
      itemDeliveryMethod: ['List each transaction as a single line', 'List each transaction including all detail lines']
    }
  },

  methods: {
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
