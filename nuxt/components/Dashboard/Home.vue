<template>
  <div id="home">
    <v-container fluid>
      <v-row>
        <v-col cols="12" md="12" xl="12">
          <v-app-bar
            flat
            elevation="0"
            style="border-bottom: thin solid rgba(0, 0, 0, 0.12) !important;"
          >
            <v-img  max-width="90" max-height="90" :src="logo"></v-img>
            <v-app-bar-title class="text-h5 ml-4">{{ companyName }}</v-app-bar-title>

            <v-spacer></v-spacer>

            <v-btn text color="secondary" @click="openSetting">Resume Setup</v-btn>
            <template #extension>
              <v-tabs align-with-title>
                <v-tab @click="selectComponent = 'done'">Get things done</v-tab>
                <v-tab @click="selectComponent = 'overview'">Business overview</v-tab>
              </v-tabs>
            </template>
          </v-app-bar>
        </v-col>

        <v-col cols="12" md="12" xl="10">
          <component
            :is="selectComponent"
            ref="childComponent"
          ></component>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
const gradients = [
  ['#222'],
  ['#42b3f4'],
  ['red', 'orange', 'yellow'],
  ['purple', 'violet'],
  ['#00c6ff', '#F0F', '#FF0'],
  ['#f72047', '#ffd200', '#1feaea'],
]

import GetThingDone from "./GetThingDone";
import BusinessOverview from "./BusinessOverview";

export default {
  name: 'DashboardPage',

  components: {
    done: GetThingDone,
    overview: BusinessOverview
  },

  data() {
    return {
      logo: null,
      companyName: '',
      form: {},
      url: '/api/entities',
      selectComponent: 'done',
      loading: true,
      width: 2,
      radius: 10,
      padding: 8,
      lineCap: 'round',
      gradient: gradients[5],
      value: [0, 2, 5, 9, 5, 10, 3, 5, 0, 0, 1, 8, 2, 9, 0],
      labels: [
        '12am',
        '3am',
        '6am',
        '9am',
        '12pm',
        '3pm',
        '6pm',
        '9pm',
      ],
      gradientDirection: 'top',
      gradients,
      fill: false,
      type: 'trend',
      autoLineWidth: false,
    }
  },

  mounted() {
    this.getDataFromApi()
  },

  methods: {
    openSetting() {
      this.$nuxt.$emit('openSetting', {
        item: {
          text: 'Account and Settings'
        }
      })
    },

    getDataFromApi() {
      this.loading = true
      const vm = this
      this.$axios
        .get(this.url, {
          params: {
            options: vm.options,
          },
        })
        .then((res) => {
          this.loading = false
          this.form = Object.assign({}, res.data.data.rows)
          const url = res.data.data.url
          this.logo = url + '/files/logo/' + res.data.data.logo.value
          this.companyName = this.form.name
        })
        .catch((err) => {
          this.loading = false
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },
  }
}
</script>
