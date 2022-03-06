<template>
  <v-form @keyup.native.enter="register">
    <v-flex xs12>
      <v-card flat>
        <v-card-title primary-title>
          <template v-if="loading">
            <v-progress-linear height="10" indeterminate></v-progress-linear>
          </template>

          Data Orang Tua
          <hr />
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.name_father"
                :error-messages="errors.name_father"
                filled
                dense
                label="Nama Ayah"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.nik_father"
                :error-messages="errors.nik_father"
                filled
                dense
                label="NIK Ayah"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.father_born_place"
                :error-messages="errors.father_born_place"
                filled
                dense
                label="Tempat Lahir Ayah"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
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
                    v-model="form.father_dob"
                    :error-messages="errors.father_dob"
                    label="Tanggal Lahir Ayah"
                    prepend-icon="mdi-calendar"
                    readonly
                    persistent-hint
                    filled
                    dense
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="form.father_dob"
                  no-title
                  @input="menu1 = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="2">
              <v-select
                v-model="form.father_education"
                :error-messages="errors.father_education"
                :items="itemSchool"
                filled
                dense
                label="Pendidikan Ayah"
                item-text="name"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.father_job"
                :error-messages="errors.father_job"
                :items="itemJob"
                filled
                dense
                label="Pekerjaan Ayah"
                item-text="name"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.father_income"
                :error-messages="errors.father_income"
                :items="itemIncome"
                filled
                dense
                label="Penghasilan Ayah"
                item-text="value"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="4">
              <v-select
                v-model="form.father_special_need"
                :error-messages="errors.father_special_need"
                :items="itemSpecialNeeds"
                filled
                dense
                label="Berkebutuhan Khusus Ayah"
                item-text="name"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.name_mother"
                :error-messages="errors.name_mother"
                filled
                dense
                label="Nama Ibu"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.nik_mother"
                :error-messages="errors.nik_mother"
                filled
                dense
                label="NIK Ibu"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.mother_born_place"
                :error-messages="errors.mother_born_place"
                filled
                dense
                label="Tempat Lahir Ibu"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-menu
                ref="menu2"
                v-model="menu2"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template #activator="{ on, attrs }">
                  <v-text-field
                    v-model="form.mother_dob"
                    :error-messages="errors.mother_dob"
                    label="Tanggal Lahir Ibu"
                    prepend-icon="mdi-calendar"
                    readonly
                    persistent-hint
                    filled
                    dense
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="form.mother_dob"
                  no-title
                  @input="menu2 = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="2">
              <v-select
                v-model="form.mother_education"
                :error-messages="errors.mother_education"
                :items="itemSchool"
                filled
                dense
                item-text="name"
                item-value="id"
                label="Pendidikan Ibu"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.mother_job"
                :error-messages="errors.mother_job"
                :items="itemJob"
                filled
                dense
                label="Pekerjaan Ibu"
                item-text="name"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.mother_income"
                :error-messages="errors.mother_income"
                :items="itemIncome"
                filled
                dense
                label="Penghasilan Ibu"
                item-text="value"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="4">
              <v-select
                v-model="form.mother_special_need"
                :error-messages="errors.mother_special_need"
                :items="itemSpecialNeeds"
                filled
                dense
                label="Berkebutuhan Khusus Ibu"
                item-text="name"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.guardian_parent_name"
                :error-messages="errors.guardian_parent_name"
                filled
                dense
                label="Nama Wali"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.guardian_parent_nik"
                :error-messages="errors.guardian_parent_nik"
                filled
                dense
                label="NIK Wali"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.guardian_parent_born_place"
                :error-messages="errors.guardian_parent_born_place"
                filled
                dense
                label="Tempat Lahir Wali"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-menu
                ref="menu3"
                v-model="menu3"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template #activator="{ on, attrs }">
                  <v-text-field
                    v-model="form.guardian_parent_dob"
                    :error-messages="errors.guardian_parent_dob"
                    label="Tanggal Lahir Wali"
                    prepend-icon="mdi-calendar"
                    readonly
                    persistent-hint
                    filled
                    dense
                    v-bind="attrs"
                    v-on="on"
                  ></v-text-field>
                </template>
                <v-date-picker
                  v-model="form.guardian_parent_dob"
                  no-title
                  @input="menu3 = false"
                >
                </v-date-picker>
              </v-menu>
            </v-col>

            <v-col cols="12" md="2">
              <v-select
                v-model="form.guardian_parent_education"
                :error-messages="errors.guardian_parent_education"
                :items="itemSchool"
                filled
                dense
                label="Pendidikan Wali"
                item-text="name"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.guardian_parent_job"
                :error-messages="errors.guardian_parent_job"
                :items="itemJob"
                filled
                dense
                label="Pekerjaan Wali"
                item-text="name"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.guardian_parent_income"
                :error-messages="errors.guardian_parent_income"
                :items="itemIncome"
                filled
                dense
                label="Penghasilan Wali"
                item-text="value"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="2">
              <v-select
                v-model="form.guardian_parent_special_need"
                :error-messages="errors.guardian_parent_special_need"
                :items="itemSpecialNeeds"
                filled
                dense
                label="Berkebutuhan Khusus Wali"
                item-text="name"
                item-value="id"
                required
              ></v-select>
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
  name: 'ParentInfo',
  data: () => ({
    form: {},
    errors: {},
    loading: false,
    dialog: false,
    menu1: '',
    menu2: '',
    menu3: '',
    itemSpecialNeeds: [],
    itemJob: [],
    itemSchool: [],
    itemIncome: [],
  }),

  methods: {
    getData(student) {
      this.loading = true
      this.getSpecialNeeds()
      this.getJob()
      this.getSchool()
      this.getIncome()

      this.dialog = true
      this.$axios.get(`/api/student-data-parent/` + student.id).then((res) => {
        this.errors = Object.assign({}, res.data.default)
        this.form = Object.assign({}, res.data.rows)
        this.loading = false
      })
    },

    getSpecialNeeds() {
      this.$axios.get(`/api/master/special-needs`).then((res) => {
        this.itemSpecialNeeds = res.data.rows
      })
    },

    getJob() {
      this.$axios.get(`/api/master/parent-job`).then((res) => {
        this.itemJob = res.data.rows
      })
    },

    getSchool() {
      this.$axios.get(`/api/master/school-status`).then((res) => {
        this.itemSchool = res.data.rows
      })
    },

    getIncome() {
      this.$axios.get(`/api/master/income`).then((res) => {
        this.itemIncome = res.data.rows
      })
    },

    getRegistrationData() {
      this.$axios.get(`/api/student-data-parent`).then((res) => {
        this.errors = Object.assign({}, res.data.rows)
        this.form = Object.assign({}, res.data.rows)
      })
    },

    register() {
      this.loading = true
      this.$axios.post(`/api/student-data-parent`, this.form).then((res) => {
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
