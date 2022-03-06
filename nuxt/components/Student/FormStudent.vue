<template>
  <v-form @keyup.native.enter="register">
    <v-flex xs12>
      <v-card>
        <v-card-title primary-title>
          <template v-if="loading">
            <v-progress-linear height="10" indeterminate></v-progress-linear>
          </template>

          Data Pribadi
          <hr />
        </v-card-title>
        <v-card-text>
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
              <small>*Ketik manual untuk mengisi sekolah tidak muncul!</small>
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
                  >Untuk menulis decimal dapat menggunakan tanda titik misalkan
                  80.50</small
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
            <v-icon> mdi-file-document-box </v-icon>
            Ubah
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-flex>
  </v-form>
</template>

<script>
export default {
  name: 'FormStudent',
  data: () => ({
    form: {},
    errors: {},
    default: {},
    list_school: {},
    menu1: '',
    messages: '',
    messageTitle: '',
    items: [1, 2, 3, 4, 5],
    itemYesNo: [
      { text: 'Ya', value: 'Y' },
      { text: 'Tidak', value: 'N' },
    ],
    itemGender: ['Laki-laki', 'Perempuan'],
    itemMajor: [],
    itemExpertise: [],
    loading: false,
    dialogError: false,
    min_date: null,
  }),

  computed: {
    formTitle() {
      return process.env.APP_NAME
    },
  },

  methods: {
    getHomeData(student) {
      this.$axios.get(`/api/home-data`).then((res) => {
        this.errors = Object.assign({}, res.data.form)
        this.default = Object.assign({}, res.data.form)
      })

      this.$axios.get(`/api/prospective-students/` + student.id).then((res) => {
        this.getMajor()

        this.form = Object.assign({}, res.data.form)
        this.min_date = res.data.min_date
        this.list_school = res.data.list_school

        setTimeout(() => {
          this.selectExpertise()
        }, 500)
      })
    },

    close() {
      this.$emit('closeAction')
    },

    register() {
      this.loading = true
      this.errors = Object.assign({}, this.default)
      this.$axios
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

    getMajor() {
      this.$axios.get(`/api/all-major`).then((res) => {
        this.itemMajor = res.data.major
      })
    },

    calcTotal(type) {
      let total = 0
      for (let i = 1; i <= 5; i++) {
        const lesson = this.form['lesson_' + type + i]
        let lessonVal = 0
        if (lesson && !isNaN(lesson)) {
          lessonVal = parseFloat(lesson)
        } else {
          lessonVal = 0
        }
        total += parseFloat(lessonVal)
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
