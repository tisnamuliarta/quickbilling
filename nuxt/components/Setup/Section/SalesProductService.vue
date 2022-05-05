<template>
  <v-row no-gutters>
    <v-col cols="12" md="2" class="pa-1">
      <span class="text-subtitle-1">Product And Services</span>
    </v-col>
    <v-col cols="12" md="8" class="pa-1">
      <FormSectionView v-if="companyNameView">
        <template #content>
          <v-row no-gutters @click="companyNameView = false">
            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Show Product/Services column in sales forms
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_product_in_form)"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Show SKU Column
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_show_sku)"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Turn On Price Rules
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_price_list_rules)"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Track Quantity and price/rate
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_track_quantity_price)"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Track Inventory Quantity on hand
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_track_inventory_qty)"></span>
            </v-col>
          </v-row>
        </template>
      </FormSectionView>

      <FormSectionEdit ref="sectionEdit" v-else @save="save" @cancel="cancel">
        <template #content>
          <v-row no-gutters>
            <v-col cols="12" md="5" class="pa-2">
              <v-checkbox
                v-model="form.sales_product_in_form"
                label="Show Product/Services column in sales forms"
                hide-details="auto"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_product_in_form)"></span>
            </v-col>

            <v-col cols="12" md="5" class="pa-2">
              <v-checkbox
                v-model="form.sales_show_sku"
                label="Show SKU Column"
                hide-details="auto"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_show_sku)"></span>
            </v-col>

            <v-col cols="12" md="5" class="pa-2">
              <v-checkbox
                v-model="form.sales_price_list_rules"
                label="Turn On Price Rules"
                hide-details="auto"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_price_list_rules)"></span>
            </v-col>

            <v-col cols="12" md="5" class="pa-2">
              <v-checkbox
                v-model="form.sales_track_quantity_price"
                label="Track Quantity and price/rate"
                hide-details="auto"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_track_quantity_price)"></span>
            </v-col>

            <v-col cols="12" md="5" class="pa-2">
              <v-checkbox
                v-model="form.sales_track_inventory_qty"
                label="Track Inventory Quantity on hand"
                hide-details="auto"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_track_inventory_qty)"></span>
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
      itemDeliveryMethod: ['Print Later', 'Send Later', 'None']
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
