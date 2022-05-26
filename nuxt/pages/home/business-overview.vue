<template>
  <v-row>
    <v-col cols="12" md="9">
      <v-row>
        <v-col cols="12" md="4">
          <v-card class="mx-auto">
            <v-card-text>
              <div class="subtitle-1">INVOICES</div>
            </v-card-text>
            <v-card-text></v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card class="mx-auto">
            <v-card-text>
              <div class="subtitle-1">EXPENSES</div>
            </v-card-text>
            <v-card-text></v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card class="mx-auto">
            <v-card-text>
              <div class="subtitle-1">PROFIT AND LOSS</div>
            </v-card-text>
            <v-card-text></v-card-text>
          </v-card>
        </v-col>

        <v-col cols="12" md="4">
          <v-card class="mx-auto">
            <v-card-text>
              <div class="subtitle-1">SALES</div>
            </v-card-text>
            <v-card-text></v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-col>

    <v-col cols="12" md="3">
      <v-card class="mx-auto">
        <v-card-text>
          <div class="subtitle-1">BANK ACCOUNTS</div>
        </v-card-text>
        <v-card-text></v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'BusinessOverview',

  activated() {
    this.$nuxt.$emit('extensionSetting', {
      tabs: [
        {
          title: 'Business Overview',
          route: '/home/business-overview',
        },
        {
          title: 'Get Thing Done',
          route: '/home/getthingdone',
        },
      ],
      show: true,
      showBtn: false,
    })
  },
}
</script>
