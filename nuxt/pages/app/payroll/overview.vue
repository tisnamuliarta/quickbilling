<template>
  <v-container>
    <v-row>
      <v-col cols="12" md="3">
        <v-card class="mx-auto">
          <v-card-text>
            <div class="subtitle-1">SHORTCUTS</div>
          </v-card-text>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12" sm="6">
                  <v-btn icon>
                    <v-icon x-large>mdi-receipt</v-icon>
                  </v-btn>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="3">
        <v-card class="mx-auto">
          <v-card-text>
            <div class="subtitle-1">SHORTCUTS</div>
          </v-card-text>
          <v-card-text></v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'PayrollOverview',

  head() {
    return {
      title: 'Payroll Overview',
    }
  },

  activated() {
    this.$nuxt.$emit('extensionSetting', {
      show: false,
      showBtn: false,
    })
  },

  created() {
    this.$nuxt.$on('getDataFromApi', ($event) => this.getDataFromApi($event))
  },

  methods: {
    getDataFromApi() {
      if (this.$refs.tableDocument) {
        this.$refs.tableDocument.getDataFromApi()
      }
    },
  },
}
</script>
