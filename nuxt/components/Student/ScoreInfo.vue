<template>
  <v-form @keyup.native.enter="register">
    <v-flex xs12>
      <v-card>
        <v-card-title primary-title>
          <template v-if="loading">
            <v-progress-linear height="10" indeterminate></v-progress-linear>
          </template>

          Data Nilai
          <hr />
        </v-card-title>
        <v-card-text>
          <v-row>
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
                filled
                type="number"
                dense
                readonly
                label="Rata -rata"
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
  name: 'ScoreInfo',
  data: () => ({
    form: {},
    errors: {},
    dialog: false,
    loading: false,
    items: [1, 2, 3, 4, 5],
  }),

  methods: {
    register() {},

    close() {
      this.$emit('closeAction')
    },

    getData(student) {
      this.dialog = true
      this.loading = true
      this.$axios.get(`/api/student-score/` + student.id).then((res) => {
        this.errors = Object.assign({}, res.data.default)
        this.form = Object.assign({}, res.data.rows)
        this.loading = false
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
  },
}
</script>

<style scoped></style>
