<template>
  <v-form @keyup.native.enter="register">
    <v-flex xs12>
      <v-card>
        <v-card-title primary-title>
          <template v-if="loading">
            <v-progress-linear height="10" indeterminate></v-progress-linear>
          </template>

          Data Bantuan
          <hr />
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.kps_no"
                :error-messages="errors.kps_no"
                filled
                dense
                label="Nomor Kartu Kelurga Sejahtera"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.is_kps_receiver"
                :error-messages="errors.is_kps_receiver"
                :items="itemYesNo"
                filled
                dense
                label="Penerima KPS/ PKH"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.kps_no"
                :error-messages="errors.kps_no"
                filled
                dense
                label="No KPS/PKH"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.is_pip_worthy"
                :error-messages="errors.is_pip_worthy"
                :items="itemYesNo"
                filled
                dense
                label="Usulan dari Sekolah (Layak PIP)"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.pip_worthy_reason"
                :error-messages="errors.pip_worthy_reason"
                :items="itemReason"
                filled
                dense
                label="Alasan Layak (PIP)"
                item-text="name"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.is_kip_receiver"
                :error-messages="errors.is_kip_receiver"
                :items="itemYesNo"
                filled
                dense
                label="Penerima KIP"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.is_kip_physical_receiver"
                :error-messages="errors.is_kip_physical_receiver"
                :items="itemYesNo"
                filled
                dense
                label="Penerima Fisik KIP"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.pip_no"
                :error-messages="errors.pip_no"
                filled
                dense
                label="Nomor PIP"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.pip_name"
                :error-messages="errors.pip_name"
                filled
                dense
                label="Nama di PIP"
                required
              ></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>

        <v-card-actions>
          <v-btn color="red darken-1" dark small @click="close"> Tutup </v-btn>
          <v-spacer />
          <v-btn
            small
            :loading="loading"
            elevation="3"
            color="success"
            class="white--text ml-1"
            @click="register"
          >
            <v-icon left> mdi-file-document-box </v-icon>
            Ubah
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-form>
</template>

<script>
export default {
  name: 'DataHelp',
  data: () => ({
    form: {},
    errors: {},
    loading: false,
    dialog: false,
    itemReason: [],
    itemYesNo: [
      { text: 'Ya', value: 'Y' },
      { text: 'Tidak', value: 'N' },
    ],
  }),

  methods: {
    getData(student) {
      this.dialog = true
      this.loading = true
      this.getPipReason()
      this.$axios.get(`/api/student-data-help/` + student.id).then((res) => {
        this.errors = Object.assign({}, res.data.default)
        this.form = Object.assign({}, res.data.rows)
        this.loading = false
      })
    },

    getPipReason() {
      this.$axios.get(`/api/master/pip-reason`).then((res) => {
        this.itemReason = res.data.rows
      })
    },

    getRegistrationData() {
      this.$axios.get(`/api/student-data-help`).then((res) => {
        this.errors = Object.assign({}, res.data.rows)
        this.form = Object.assign({}, res.data.rows)
      })
    },

    register() {
      this.loading = true
      this.$axios.post(`/api/student-data-help`, this.form).then((res) => {
        this.loading = false
        this.$swal(res.data.message)
        if (res.data.error) {
          this.errors = res.data.message
        } else {
          this.close()
        }
      })
    },

    close() {
      this.$emit('closeAction')
    },
  },
}
</script>

<style scoped></style>
