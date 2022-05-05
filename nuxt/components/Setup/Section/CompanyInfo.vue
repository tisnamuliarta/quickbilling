<template>
  <v-row no-gutters>
    <v-col cols="12" md="2" class="pa-1">
      <span class="text-subtitle-1">Company Info</span>
    </v-col>
    <v-col cols="12" md="8" class="pa-1">
      <FormSectionView v-if="companyNameView">
        <template #content>
          <v-row no-gutters @click="companyNameView = false">
            <v-col cols="12" md="4" class="pa-2 font-weight-medium"
            >Company Email</v-col
            >
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.company_email"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium"
            >Company Phone</v-col
            >
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.company_phone"></span>
            </v-col>

            <v-col cols="12" md="4" class="pa-2 font-weight-medium"
            >Company Website</v-col
            >
            <v-col cols="12" md="8" class="pa-2">
              <span class="text-subtitle-2" v-text="form.company_website"></span>
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
                  <v-list-item-title>Company Email</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-text-field
                label="Company Email"
                v-model="form.company_email"
                filled
                dense
                hide-details="auto"
              />
            </v-col>

            <v-col cols="12" md="5" class="pa-2">Company Phone</v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-text-field
                label="Company Phone"
                v-model="form.company_phone"
                filled
                dense
                hide-details="auto"
              />
            </v-col>

            <v-col cols="12" md="5" class="pa-2">Company Website</v-col>
            <v-col cols="12" md="7" class="pa-2">
              <v-text-field
                label="Company Website"
                v-model="form.company_website"
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
      },
    },

    logo: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      companyNameView: true,
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
  },
}
</script>
