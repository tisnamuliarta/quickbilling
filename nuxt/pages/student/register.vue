<template>
  <v-container>
    <v-form v-if="ppdb.open_date === date_now" @keyup.native.enter="register">
      <v-flex xs12 md8 offset-md2>
        <v-card>
          <v-card-title primary-title>
            <template v-if="loading">
              <v-progress-linear height="10" indeterminate></v-progress-linear>
            </template>

            Daftar PPDB
            <hr />
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" md="12">
                  <span class="card-description"> Data Pribadi </span>
                  <hr />
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="form.name"
                    :error-messages="errors.name"
                    filled
                    dense
                    label="Nama Lengkap"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="form.born_place"
                    :error-messages="errors.born_place"
                    filled
                    dense
                    label="Tempat Lahir"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-menu
                    ref="menu"
                    v-model="menu1"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template #activator="{ on, attrs }">
                      <v-text-field
                        v-model="form.dob"
                        :error-messages="errors.dob"
                        placeholder="Tanggal Lahir"
                        prepend-icon="mdi-calendar"
                        readonly
                        persistent-hint
                        filled
                        dense
                        :min="min_date"
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="form.dob"
                      no-title
                      @input="menu1 = false"
                    >
                    </v-date-picker>
                  </v-menu>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="form.no_nisn"
                    :error-messages="errors.no_nisn"
                    filled
                    dense
                    label="NISN"
                    required
                  ></v-text-field>
                </v-col>
                <v-flex md12>
                  <small
                    >*Ketik manual untuk mengisi sekolah tidak muncul!</small
                  >
                </v-flex>
                <v-col cols="12" md="6">
                  <v-autocomplete
                    v-model="form.old_school"
                    :error-messages="errors.old_school"
                    :items="list_school"
                    dense
                    filled
                    item-text="name"
                    item-value="name"
                    label="Sekolah SMP Asal"
                  ></v-autocomplete>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="form.no_nik"
                    :error-messages="errors.no_nik"
                    filled
                    dense
                    label="NIK"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-select
                    v-model="form.gender"
                    :error-messages="errors.gender"
                    :items="itemGender"
                    filled
                    dense
                    label="Jenis Kelamin"
                    required
                  ></v-select>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="form.address"
                    :error-messages="errors.address"
                    filled
                    dense
                    label="Alamat"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                  <v-select
                    v-model="form.hasKIP"
                    :error-messages="errors.hasKIP"
                    :items="itemYesNo"
                    filled
                    dense
                    label="Memiliki Kartu KIP"
                    required
                  ></v-select>
                </v-col>

                <v-col cols="12" md="6">
                  <v-text-field
                    v-model="form.no_phone"
                    :error-messages="errors.no_phone"
                    filled
                    dense
                    label="No HP"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <span class="card-description">
                    Nilai Raport
                    <small
                      >Untuk menulis decimal dapat menggunakan tanda titik
                      misalkan 80.50</small
                    >
                  </span>
                  <hr />
                </v-col>

                <v-col cols="12" md="12">
                  <span class="card-description"> Bahasa Indonesia </span>
                </v-col>

                <v-col
                  v-for="(item, index) in items"
                  :key="'lesson_bindo' + index"
                  cols="6"
                  md="2"
                  xs="6"
                >
                  <v-text-field
                    v-model="form['lesson_bindo' + item]"
                    :error-messages="errors['lesson_bindo' + item]"
                    filled
                    dense
                    type="number"
                    :label="'Semester ' + item"
                    required
                    @change="calcTotal('bindo')"
                  ></v-text-field>
                </v-col>

                <v-col cols="6" md="2" xs="6">
                  <v-text-field
                    v-model="form.average_bindo"
                    :error-messages="errors.average_bindo"
                    filled
                    dense
                    readonly
                    type="number"
                    label="Rata -rata"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <span class="card-description"> Bahasa Inggris </span>
                </v-col>

                <v-col
                  v-for="(item, index) in items"
                  :key="'lesson_bing' + index"
                  cols="6"
                  md="2"
                  xs="6"
                >
                  <v-text-field
                    v-model="form['lesson_bing' + item]"
                    :error-messages="errors['lesson_bing' + item]"
                    filled
                    dense
                    type="number"
                    :label="'Semester ' + item"
                    required
                    @change="calcTotal('bing')"
                  ></v-text-field>
                </v-col>

                <v-col cols="6" md="2" xs="6">
                  <v-text-field
                    v-model="form.average_bing"
                    :error-messages="errors.average_bing"
                    filled
                    dense
                    readonly
                    type="number"
                    label="Rata-rata"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <span class="card-description"> Matematika </span>
                </v-col>

                <v-col
                  v-for="(item, index) in items"
                  :key="'lesson_mtk' + index"
                  cols="6"
                  md="2"
                  xs="6"
                >
                  <v-text-field
                    v-model="form['lesson_mtk' + item]"
                    :error-messages="errors['lesson_mtk' + item]"
                    filled
                    dense
                    type="number"
                    :label="'Semester ' + item"
                    required
                    @change="calcTotal('mtk')"
                  ></v-text-field>
                </v-col>

                <v-col cols="6" md="2" xs="6">
                  <v-text-field
                    v-model="form.average_mtk"
                    :error-messages="errors.average_mtk"
                    filled
                    type="number"
                    dense
                    readonly
                    label="Rata -rata"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <span class="card-description"> IPA </span>
                </v-col>

                <v-col
                  v-for="(item, index) in items"
                  :key="'lesson_ipa' + index"
                  cols="6"
                  md="2"
                  xs="6"
                >
                  <v-text-field
                    v-model="form['lesson_ipa' + item]"
                    :error-messages="errors['lesson_ipa' + item]"
                    filled
                    dense
                    type="number"
                    :label="'Semester ' + item"
                    required
                    @change="calcTotal('ipa')"
                  ></v-text-field>
                </v-col>

                <v-col cols="6" md="2" xs="6">
                  <v-text-field
                    v-model="form.average_ipa"
                    :error-messages="errors.average_ipa"
                    filled
                    type="number"
                    dense
                    readonly
                    label="Rata -rata"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <span class="card-description">
                    Jurusan dan Bidang Keahlian
                  </span>
                  <hr />
                </v-col>

                <v-col cols="12" md="6">
                  <v-select
                    v-model="form.major_id"
                    :error-messages="errors.major_id"
                    :items="itemMajor"
                    filled
                    dense
                    required
                    label="Jurusan"
                    item-text="name"
                    item-value="id"
                    clearable
                    @change="selectExpertise()"
                  ></v-select>
                </v-col>

                <v-col cols="12" md="6">
                  <v-select
                    v-model="form.expertise_id"
                    :error-messages="errors.expertise_id"
                    :items="itemExpertise"
                    clearable
                    filled
                    dense
                    required
                    item-text="name"
                    item-value="id"
                    label="Bidang Keahlian"
                  ></v-select>
                </v-col>

                <v-col cols="12" md="12">
                  <span class="card-description"> Orang Tua atau Wali </span>
                  <hr />
                </v-col>

                <v-col cols="12" md="12">
                  <span class="card-description"> Ayah </span>
                </v-col>

                <v-col cols="12" md="4">
                  <v-text-field
                    v-model="form.name_father"
                    :error-messages="errors.name_father"
                    filled
                    dense
                    label="Nama Ayah"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="4">
                  <v-text-field
                    v-model="form.nik_father"
                    :error-messages="errors.nik_father"
                    filled
                    dense
                    type="number"
                    label="NIK"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="4">
                  <v-text-field
                    v-model="form.address_father"
                    :error-messages="errors.address_father"
                    filled
                    dense
                    label="Alamat"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <span class="card-description"> Ibu </span>
                </v-col>

                <v-col cols="12" md="4">
                  <v-text-field
                    v-model="form.name_mother"
                    :error-messages="errors.name_mother"
                    filled
                    dense
                    label="Nama Ibu"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="4">
                  <v-text-field
                    v-model="form.nik_mother"
                    :error-messages="errors.nik_mother"
                    filled
                    dense
                    type="number"
                    label="NIK"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="4">
                  <v-text-field
                    v-model="form.address_mother"
                    :error-messages="errors.address_mother"
                    filled
                    dense
                    label="Alamat"
                    required
                  ></v-text-field>
                </v-col>

                <v-col cols="12" md="12">
                  <v-checkbox
                    v-model="form.agree_tos"
                    :error-messages="errors.agree_tos"
                    label="Saya bersedia melengkapi berkas jika dinyatakan LULUS Seleksi PPDB dan siap dinyatakan
                    TIDAK LULUS jika tidak melengkapi berkas tepat pada waktunya"
                  >
                  </v-checkbox>
                </v-col>

                <v-col cols="12" md="12">
                  <v-checkbox
                    v-model="form.parentCheck"
                    :error-messages="errors.parentCheck"
                    :label="
                      'Telah mendapatkan izin dari orang tua untuk melakukan pendaftaran di ' +
                      formTitle
                    "
                  >
                  </v-checkbox>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions>
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
              Daftar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>

      <v-dialog v-model="dialogError" persistent max-width="600px">
        <v-card>
          <v-card-title>
            <span class="headline">{{ messageTitle }}</span>
          </v-card-title>
          <v-card-text>
            {{ messages }}
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red darken-1" text small @click="dialogError = false">
              Tutup
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-form>
    <v-flex v-else xs12 md8 offset-md2>
      <v-card>
        <v-card-title primary-title>
          Pendaftaran Online Belum di Buka!
        </v-card-title>
        <v-card-text>
          Pendaftaran online akan dibuka pada tanggal {{ ppdb.open_date }}
        </v-card-text>
      </v-card>
    </v-flex>
  </v-container>
</template>

<script>
export default {
  name: 'StudentRegister',
  layout: 'default',
  data: () => ({
    form: {},
    errors: {},
    default: {},
    list_school: {},
    menu1: '',
    messages: '',
    messageTitle: '',
    items: [1, 2, 3, 4, 5],
    itemYesNo: ['Ya', 'Tidak'],
    itemGender: ['Laki-laki', 'Perempuan'],
    itemMajor: [],
    itemExpertise: [],
    ppdb: [],
    date_now: null,
    loading: false,
    dialogError: false,
    min_date: null,
  }),

  computed: {
    formTitle() {
      return process.env.APP_NAME
    },
  },

  created() {
    this.getHomedata()
    this.getMajor()
  },

  methods: {
    getHomedata() {
      this.$axios.get(`/api/home-data`).then((res) => {
        this.form = Object.assign({}, res.data.form)
        this.errors = Object.assign({}, res.data.form)
        this.default = Object.assign({}, res.data.form)
        this.min_date = res.data.min_date
        this.list_school = res.data.list_school
        this.ppdb = res.data.ppdb
        this.date_now = res.data.date_now
      })
    },

    getMajor() {
      this.$axios.get(`/api/all-major`).then((res) => {
        this.itemMajor = res.data.major
      })
    },

    register() {
      this.loading = true
      this.errors = Object.assign({}, this.default)
      axios
        .post(`/api/ppdb/register`, this.form)
        .then((res) => {
          this.loading = false
          this.messages = res.data.messages
          if (res.data.error) {
            this.dialogError = true
            this.messageTitle = 'Error'
          } else {
            this.messageTitle = 'Berhasil!'
            this.dialogError = true
            this.errors = Object.assign({}, this.default)
            this.form = Object.assign({}, this.default)
          }
        })
        .catch((err) => {
          this.loading = false
          this.errors = Object.assign({}, err.response.data.errors)
          this.messages = 'Terdapat Error, Periksa Inputan Kembali!'
          this.messageTitle = 'Error'
          this.dialogError = true
        })
    },

    calcTotal(type) {
      let total = 0
      for (let i = 1; i <= 5; i++) {
        const lesson = this.form['lesson_' + type + i]
        let lesson_val = 0
        if (lesson && !isNaN(lesson)) {
          lesson_val = parseFloat(lesson)
        } else {
          lesson_val = 0
        }
        total += parseFloat(lesson_val)
      }
      this.form['average_' + type] = total / 5
    },

    selectExpertise(item) {
      this.$axios
        .get(`/api/expertise-by-major`, {
          params: {
            major: this.form.major_id,
          },
        })
        .then((res) => {
          this.itemExpertise = res.data
        })
    },
  },
}
</script>

<style scoped>
.col,
.col-1,
.col-2,
.col-3,
.col-4,
.col-5,
.col-6,
.col-7,
.col-8,
.col-9,
.col-10,
.col-11,
.col-12,
.col-auto,
.col-lg,
.col-lg-1,
.col-lg-2,
.col-lg-3,
.col-lg-4,
.col-lg-5,
.col-lg-6,
.col-lg-7,
.col-lg-8,
.col-lg-9,
.col-lg-10,
.col-lg-11,
.col-lg-12,
.col-lg-auto,
.col-md,
.col-md-1,
.col-md-2,
.col-md-3,
.col-md-4,
.col-md-5,
.col-md-6,
.col-md-7,
.col-md-8,
.col-md-9,
.col-md-10,
.col-md-11,
.col-md-12,
.col-md-auto,
.col-sm,
.col-sm-1,
.col-sm-2,
.col-sm-3,
.col-sm-4,
.col-sm-5,
.col-sm-6,
.col-sm-7,
.col-sm-8,
.col-sm-9,
.col-sm-10,
.col-sm-11,
.col-sm-12,
.col-sm-auto,
.col-xl,
.col-xl-1,
.col-xl-2,
.col-xl-3,
.col-xl-4,
.col-xl-5,
.col-xl-6,
.col-xl-7,
.col-xl-8,
.col-xl-9,
.col-xl-10,
.col-xl-11,
.col-xl-12,
.col-xl-auto {
  width: 100%;
  padding: 0 12px !important;
}
</style>
