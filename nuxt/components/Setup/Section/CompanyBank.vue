<template>
  <v-row no-gutters>
    <v-col cols="12" md="2" class="pa-1">
      <span class="text-subtitle-1">Company Bank</span>
    </v-col>
    <v-col cols="12" md="8" class="pa-1">
      <FormSectionView v-if="companyNameView">
        <template #content>
          <v-row no-gutters @click="companyNameView = false">
            <v-col cols="12" md="4" class="pa-2 font-weight-medium">
              Bank Name
            </v-col>
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.company_bank_name"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium"
            >Bank Branch
            </v-col
            >
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.company_bank_branch"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium"
            >Bank Address
            </v-col
            >
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.company_bank_address"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium"
            >Bank Account Number
            </v-col
            >
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.company_bank_account_number"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium"
            >Bank Account Name
            </v-col
            >
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.company_bank_account_name"></span>
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
                  <v-list-item-title>Bank Name</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-text-field
                label="Company Address"
                v-model="form.company_bank_name"
                filled
                dense
                hide-details="auto"
              />
            </v-col>

            <v-col cols="12" md="5" class="pa-2">
              <v-list-item two-line class="pa-0">
                <v-list-item-content>
                  <v-list-item-title>Bank Branch</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-text-field
                label="Bank Branch"
                v-model="form.company_bank_branch"
                filled
                dense
                hide-details="auto"
              />
            </v-col>

            <v-col cols="12" md="5" class="pa-2">Bank Address</v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-text-field
                label="Address"
                v-model="form.company_bank_address"
                filled
                dense
                hide-details="auto"
              />
            </v-col>

            <v-col cols="12" md="5" class="pa-2">Bank Address</v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-text-field
                label="Bank Account Number"
                v-model="form.company_bank_account_number"
                filled
                dense
                hide-details="auto"
              />
            </v-col>

            <v-col cols="12" md="5" class="pa-2">Bank Address</v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-text-field
                label="Bank Account Name"
                v-model="form.company_bank_account_name"
                filled
                dense
                hide-details="auto"
              />
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
      companyNameView: true
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
