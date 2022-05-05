<template>
  <v-row no-gutters>
    <v-col cols="12" md="2" class="pa-1">
      <span class="text-subtitle-1">Bill and expenses</span>
    </v-col>
    <v-col cols="12" md="8" class="pa-1">
      <FormSectionView v-if="companyNameView">
        <template #content>
          <v-row no-gutters @click="companyNameView = false">
            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Use purchase orders
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.expenses_purcase_order)"></span>
            </v-col>
          </v-row>
        </template>
      </FormSectionView>

      <FormSectionEdit ref="sectionEdit" v-else @save="save" @cancel="cancel">
        <template #content>
          <v-row no-gutters>
            <v-col cols="12" md="5" class="pa-2">
              <v-checkbox
                v-model="form.expenses_purcase_order"
                label="Use purchase orders"
                hide-details="auto"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <span
                class="text-subtitle-2"
                v-text="$formatter.formatCheckBox(form.expenses_purcase_order)"
              ></span>
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
