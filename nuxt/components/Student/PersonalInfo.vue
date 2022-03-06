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
            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.no_bird_card"
                :error-messages="errors.no_bird_card"
                filled
                dense
                label="No Akta Lahir"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.religion_id"
                :error-messages="errors.religion_id"
                :items="itemReligion"
                filled
                dense
                label="Agama & Kepercayaan"
                item-text="name"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.special_need_id"
                :error-messages="errors.special_need_id"
                :items="itemSpecialNeeds"
                filled
                dense
                label="Kebutuhan khusus"
                item-text="name"
                item-value="id"
                required
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.nationality"
                :error-messages="errors.nationality"
                filled
                dense
                label="Kewarganegaraan"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.province_id"
                :error-messages="errors.province_id"
                :items="itemProvince"
                filled
                dense
                label="Provinsi"
                required
                item-text="name"
                item-value="id"
                @change="getRegency()"
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.regency_id"
                :error-messages="errors.regency_id"
                :items="itemRegency"
                filled
                dense
                label="Kabupaten"
                required
                item-text="name"
                item-value="id"
                @change="getDistrict()"
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.district_id"
                :error-messages="errors.district_id"
                :items="itemDistrict"
                filled
                dense
                label="Kecamatan"
                required
                item-text="name"
                item-value="id"
                @change="getVillage()"
              ></v-select>
            </v-col>

            <v-col cols="12" md="3">
              <v-select
                v-model="form.village_id"
                :error-messages="errors.village_id"
                :items="itemVillage"
                filled
                dense
                label="Desa/kelurahan"
                required
                item-text="name"
                item-value="id"
              ></v-select>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.dusun_name"
                :error-messages="errors.dusun_name"
                filled
                dense
                label="Dusun"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="1">
              <v-text-field
                v-model="form.rt_name"
                :error-messages="errors.rt_name"
                filled
                dense
                label="RT"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="1">
              <v-text-field
                v-model="form.rw_name"
                :error-messages="errors.rw_name"
                filled
                dense
                label="RW"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.zip_code"
                :error-messages="errors.zip_code"
                filled
                dense
                label="Kode Pos"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-select
                v-model="form.residence_id"
                :error-messages="errors.residence_id"
                :items="itemResident"
                filled
                dense
                label="Tempat tinggal"
                required
                item-text="name"
                item-value="id"
              ></v-select>
            </v-col>

            <v-col cols="12" md="2">
              <v-select
                v-model="form.transportation_id"
                :error-messages="errors.transportation_id"
                :items="itemTransportation"
                filled
                dense
                label="Mode Transportasi"
                required
                item-text="name"
                item-value="id"
              ></v-select>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.family_order"
                :error-messages="errors.family_order"
                filled
                dense
                label="Anak ke"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.sibling_number"
                :error-messages="errors.sibling_number"
                filled
                dense
                label="Jumlah Saudara Kandung"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-select
                v-model="form.blood_group_id"
                :error-messages="errors.blood_group_id"
                :items="itemBloodGroup"
                filled
                dense
                label="Golongan Darah"
                required
                item-text="name"
                item-value="id"
              ></v-select>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.home_phone"
                :error-messages="errors.home_phone"
                filled
                dense
                label="No Telp Rumah"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.email"
                :error-messages="errors.email"
                filled
                type="email"
                dense
                label="Email"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-select
                v-model="form.extracurricular_id"
                :error-messages="errors.extracurricular_id"
                :items="itemExtraCurricular"
                filled
                dense
                label="Jenis Extra Kulikuler"
                required
                item-text="name"
                item-value="id"
              ></v-select>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.height"
                :error-messages="errors.height"
                filled
                type="number"
                dense
                label="Tinggi badan(cm)"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.head_circumference"
                :error-messages="errors.head_circumference"
                filled
                type="number"
                dense
                label="Lingkar Kepala(cm)"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.weight"
                :error-messages="errors.weight"
                filled
                type="number"
                dense
                label="Berat Badan(Kg)"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="3">
              <v-text-field
                v-model="form.school_home_distance"
                :error-messages="errors.school_home_distance"
                filled
                type="number"
                dense
                label="Jarak Tempat Tinggal Dengan Sekolah(Km)"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="2">
              <v-text-field
                v-model="form.travel_time"
                :error-messages="errors.travel_time"
                filled
                type="number"
                dense
                label="Waktu Tempuh(Menit)"
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
  name: 'PersonalInfo',
  data: () => ({
    form: {},
    errors: {},
    default: {},
    loading: false,
    dialog: false,
    itemExtraCurricular: [],
    itemBloodGroup: [],
    itemTransportation: [],
    itemResident: [],
    itemReligion: [],
    itemSpecialNeeds: [],
    itemProvince: [],
    itemRegency: [],
    allRegency: [],
    itemDistrict: [],
    allDistrict: [],
    itemVillage: [],
    allVillage: [],
  }),

  methods: {
    getData(student) {
      this.dialog = true
      this.loading = true
      this.getExtraCurricular()
      this.getBloodGroup()
      this.getTransportation()
      this.getResident()
      this.getReligion()
      this.getSpecialNeeds()
      this.getProvince()

      this.$axios.get(`/api/student-details/` + student.id).then((res) => {
        this.errors = Object.assign({}, res.data.default)
        this.form = Object.assign({}, res.data.rows)
        if (this.form.province_id) {
          this.getRegency()
          if (this.form.regency_id) {
            this.getDistrict()
            if (this.form.district_id) {
              this.getVillage()
            }
          }
        }

        this.loading = false
      })
    },

    selectRegency() {
      const itemRegency = this.allRegency
      const province = this.form.province_id

      const regency = []
      for (let i = 0; i < itemRegency.length; i++) {
        if (itemRegency[i].province_id === province) {
          regency.push(itemRegency[i])
        }
      }

      this.itemRegency = regency
    },

    selectDistrict() {
      const itemDistrict = this.allDistrict
      const regency = this.form.regency_id

      const district = []
      for (let i = 0; i < itemDistrict.length; i++) {
        if (itemDistrict[i].regency_id === regency) {
          district.push(itemDistrict[i])
        }
      }
      this.itemDistrict = district
    },

    selectVillage() {
      const itemVillage = this.allVillage
      const district = this.form.district_id

      const village = []
      for (let i = 0; i < itemVillage.length; i++) {
        if (itemVillage[i].district_id === district) {
          village.push(itemVillage[i])
        }
      }
      this.itemVillage = village
    },
    // dont use this
    getRegistrationData() {
      this.getData()
      this.getExtraCurricular()
      this.getBloodGroup()
      this.getTransportation()
      this.getResident()
      this.getReligion()
      this.getSpecialNeeds()
      this.getProvince()

      this.$axios.get(`/api/student-details`).then((res) => {
        this.errors = Object.assign({}, res.data.default)
        this.form = Object.assign({}, res.data.rows)
        if (res.data.rows.province_id) {
          this.getRegency()
          if (res.data.rows.regency_id) {
            this.getDistrict()
            if (res.data.rows.district_id) {
              this.getVillage()
            }
          }
        }
      })
    },

    register() {
      this.loading = true
      this.$axios.post(`/api/student-details`, this.form).then((res) => {
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

    getExtraCurricular() {
      this.$axios.get(`/api/master/extracurricular`).then((res) => {
        this.itemExtraCurricular = res.data.rows
      })
    },
    getBloodGroup() {
      this.$axios.get(`/api/master/blood-group`).then((res) => {
        this.itemBloodGroup = res.data.rows
      })
    },
    getTransportation() {
      this.$axios.get(`/api/master/transportation`).then((res) => {
        this.itemTransportation = res.data.rows
      })
    },
    getResident() {
      this.$axios.get(`/api/master/resident`).then((res) => {
        this.itemResident = res.data.rows
      })
    },
    getReligion() {
      this.$axios.get(`/api/master/religion`).then((res) => {
        this.itemReligion = res.data.rows
      })
    },
    getSpecialNeeds() {
      this.$axios.get(`/api/master/special-needs`).then((res) => {
        this.itemSpecialNeeds = res.data.rows
      })
    },
    getProvince() {
      this.$axios.get(`/api/master/province`).then((res) => {
        this.itemProvince = res.data.rows
      })
    },
    getRegency() {
      const vm = this
      this.$axios
        .get(`/api/master/regency`, {
          params: {
            province_id: vm.form.province_id,
          },
        })
        .then((res) => {
          this.itemRegency = res.data.rows
          this.allRegency = res.data.rows
        })
    },
    getDistrict() {
      const vm = this
      this.$axios
        .get(`/api/master/district`, {
          params: {
            regency_id: vm.form.regency_id,
          },
        })
        .then((res) => {
          this.itemDistrict = res.data.rows
          this.allDistrict = res.data.rows
        })
    },
    getVillage() {
      const vm = this
      this.$axios
        .get(`/api/master/village`, {
          params: {
            district_id: vm.form.district_id,
          },
        })
        .then((res) => {
          this.itemVillage = res.data.rows
          this.allVillage = res.data.rows
        })
    },
  },
}
</script>

<style scoped></style>
