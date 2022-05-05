<template>
  <v-row no-gutters>
    <v-col cols="12" md="2" class="pa-1">
      <span class="text-subtitle-1">Messages</span>
    </v-col>
    <v-col cols="12" md="8" class="pa-1">
      <FormSectionView v-if="companyNameView">
        <template #content>
          <v-row no-gutters @click="companyNameView = false">
            <v-col cols="12" md="12" class="pa-2 font-weight-bold">
              Default email message sent with sales form
            </v-col>
          </v-row>
        </template>
      </FormSectionView>

      <FormSectionEdit ref="sectionEdit" v-else @save="save" @cancel="cancel">
        <template #content>
          <v-row no-gutters>
            <v-col cols="4" class="pa-2">
              <v-checkbox
                v-model="form.expenses_use_greeting"
                label="Use Greeting"
                hide-details="auto"
              ></v-checkbox>
            </v-col>

            <v-col cols="4" class="pa-2">
              <v-select
                label="Greeting"
                v-model="form.expenses_message_greeting"
                :items="itemGreeting"
                filled
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="4" class="pa-2">
              <v-select
                label="Greeting Name"
                v-model="form.expenses_greeting_name"
                :items="itemName"
                filled
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" class="pa-2">
              <v-text-field
                label="Email Subject Line"
                v-model="form.expenses_message_subject"
                filled
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" class="pa-2">
              <v-textarea
                label="Email Message"
                v-model="form.expenses_message_content"
                filled
                dense
                hide-details="auto"
              ></v-textarea>
            </v-col>

            <v-col cols="12" class="pa-2">
              <v-checkbox
                v-model="form.expenses_message_email_copy"
                label="Copy me a email"
                hide-details="auto"
              ></v-checkbox>
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
      itemGreeting: ['Dear'],
      itemName: ['[Full Name]', '[First Name]'],
      itemForm: ['Invoice', 'Quotation'],
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
