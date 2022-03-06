<template>
  <v-container>
    <v-form @keyup.native.enter="processData">
      <v-flex xs12 sm6 offset-sm3 md4 offset-md4>
        <v-card>
          <v-card-title primary-title>
            <template v-if="loading">
              <v-progress-linear height="10" indeterminate></v-progress-linear>
            </template>
            Cetak Bukti Pendaftaran
            <hr />
          </v-card-title>
          <v-card-text>
            <v-flex xs12>
              <v-text-field
                v-model="form.no_nisn"
                filled
                label="NISN"
                required
              ></v-text-field>
            </v-flex>
          </v-card-text>
          <v-card-actions>
            <v-flex class="text-right" xs12>
              <v-spacer></v-spacer>
              <v-btn color="info" small :loading="loading" @click="processData">
                Print
                <v-icon right dark> mdi-print </v-icon>
              </v-btn>
            </v-flex>
          </v-card-actions>
        </v-card>
      </v-flex>
      <v-flex v-if="error" xs12 sm4 offset-sm4>
        <div class="red darken-2 text-xs-center pa-1">
          <span class="white--text">{{ message }}</span>
        </div>
      </v-flex>
    </v-form>
  </v-container>
</template>

<script>
export default {
  name: 'PrintRegistration',
  layout: 'default',
  data: () => ({
    form: {
      no_nisn: '',
    },
    show: false,
    message: '',
    error: false,
    loading: false,
  }),

  methods: {
    processData() {
      this.loading = true
      // this.loadModal= true
      // this.$validator.validateAll()
      this.$axios
        .get('/api/ppdb/print', {
          params: this.form,
          responseType: 'arraybuffer',
        })
        .then((res) => {
          const blob = new Blob([res.data], {
            type: 'application/pdf',
          })
          const url = window.URL.createObjectURL(blob)
          const link = document.createElement('a')

          link.href = window.URL.createObjectURL(blob)
          link.download = this.form.no_nisn + '.pdf'
          document.body.appendChild(link)
          link.click()
          this.loading = false
          setTimeout(function () {
            document.body.removeChild(link)
            window.URL.revokeObjectURL(url)
          }, 100)
        })
        .catch((err) => {
          this.loading = false
          this.error = true
          this.message = err.response.data.message
          // this.loadModal= false
          this.loading = false
        })
    },

    clear() {
      this.message = ''
      this.error = false
    },
  },
}
</script>

<style scoped></style>
