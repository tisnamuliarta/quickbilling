<template>
  <DialogForm
    ref="dialogForm"
    max-width="500px"
    dialog-title="Send Email"
    button-title="Save"
  >
    <template #content>
      <v-form class="pt-0">
        <v-container fluid>
          <v-row no-gutters>
            <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-combobox
                v-model="form.send_to"
                :items="itemReceiver"
                :search-input.sync="search"
                hide-selected
                label="Send To"
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

            <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-combobox
                v-model="form.cc_email"
                :items="itemCC"
                :search-input.sync="search2"
                hide-selected
                label="CC"
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
                        No results matching "<strong>{{ search2 }}</strong>". Press <kbd>enter</kbd> to create a new one
                      </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </template>
              </v-combobox>
            </v-col>

            <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-text-field
                v-model="form.from_email"
                label="From"
                disabled
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-textarea
                rows="7"
                v-model="form.messages"
                label="Message"
                outlined
                dense
                hide-details="auto"
              ></v-textarea>
            </v-col>

            <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-btn text small>
                <v-icon left>mdi-attachment</v-icon>
                SQ Attachment.pdf
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-form>
    </template>
    <template #saveData>
      <v-btn
        color="primary"
        dark
        small
        :loading="loading"
        @click="sendEmail"
      >
        Send
      </v-btn>
    </template>
  </DialogForm>
</template>

<script>
export default {
  name: "DialogSendEmail",

  data() {
    return {
      form: {
        send_to: [],
        cc_email: [],
        from_email: this.$auth.user.email,
        messages: null,
        attachment: null
      },
      defaultItem: {},
      itemReceiver: [],
      itemCC: [],
      search: null,
      search2: null,
      loading: false,
    }
  },

  methods: {
    openEmailDialog(defaultItem) {
      this.$refs.dialogForm.openDialog()
      this.defaultItem = Object.assign({}, defaultItem)
    },

    sendEmail() {
      this.loading = true
      this.$axios.post(`/api/documents/email`, {
        form: this.form,
        defaultItem: this.defaultItem
      })
        .then(res => {
          this.$refs.dialogForm.closeDialog()
          this.$swal({
            type: 'success',
            title: 'Success',
            text: res.data.data.message,
          })
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
        .finally(res => {
          this.loading = false
        })
    }
  }
}
</script>
