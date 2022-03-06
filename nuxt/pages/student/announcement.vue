<template>
  <v-container>
    <v-form @keyup.native.enter="checkAnnouncement()">
      <v-flex xs12 sm6 offset-sm3 md4 offset-md4>
        <v-card>
          <v-card-title primary-title>
            <template v-if="loading">
              <v-progress-linear height="10" indeterminate></v-progress-linear>
            </template>
            Hasil Pengumuman Seleksi PPDB
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
            <!--            <v-flex xs12>-->
            <!--              <v-text-field-->
            <!--                :append-icon="show ? 'mdi-eye-off' : 'mdi-eye'"-->
            <!--                :type="show ? 'text' : 'password'"-->
            <!--                @click:append="show = !show"-->
            <!--                v-model="form.password"-->
            <!--                filled-->
            <!--                label="Password"-->
            <!--                required-->
            <!--              ></v-text-field>-->
            <!--            </v-flex>-->
            <!--            <v-flex xs12>-->
            <!--              <small>Untuk Password Gunakan</small>-->
            <!--            </v-flex>-->
          </v-card-text>
          <v-card-actions>
            <v-flex class="text-right" xs12>
              <v-spacer></v-spacer>
              <v-btn
                color="info"
                small
                :loading="loading"
                @click="checkAnnouncement()"
              >
                Cek Hasil
                <v-icon right dark> mdi-login-variant </v-icon>
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

    <v-dialog v-model="dialog" max-width="290">
      <v-card>
        <v-card-title>{{ header }}</v-card-title>
        <v-card-text>
          {{ message }}
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="dialog = false"> Ok </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
export default {
  name: 'StudentAnnouncement',
  layout: 'default',
  data: () => ({
    form: {
      no_nisn: '',
      password: '',
    },
    show: false,
    dialog: false,
    message: '',
    error: false,
    loading: false,
    header: '',
  }),
  methods: {
    checkAnnouncement() {
      this.loading = true
      // this.loadModal= true
      // this.$validator.validateAll()
      this.$axios
        .post('/api/ppdb/announcement', this.form)
        .then((res) => {
          this.loading = false
          this.dialog = true
          this.header = res.data.header
          this.message = res.data.status
        })
        .catch((err) => {
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
