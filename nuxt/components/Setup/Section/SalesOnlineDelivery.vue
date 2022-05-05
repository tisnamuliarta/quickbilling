<template>
  <v-row no-gutters>
    <v-col cols="12" md="2" class="pa-1">
      <span class="text-subtitle-1">Online Delivery</span>
    </v-col>
    <v-col cols="12" md="8" class="pa-1">
      <FormSectionView v-if="companyNameView">
        <template #content>
          <v-row no-gutters @click="companyNameView = false">
            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Email Option for sales form
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.sales_email_details"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              PDF Attachment
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_email_attachment)"></span>
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
                  <v-list-item-title>Email Option for sales form</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-select
                label="Email Option for sales form"
                v-model="form.sales_email_details"
                :items="itemDeliveryMethod"
                filled
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="5" class="pa-2">
              <v-checkbox
                v-model="form.sales_email_attachment"
                label="PDF Attachment"
                hide-details="auto"
              ></v-checkbox>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <span class="text-subtitle-2" v-text="$formatter.formatCheckBox(form.sales_email_attachment)"></span>
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
      itemDeliveryMethod: ['Show short summary in email', 'Show full details in email']
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
