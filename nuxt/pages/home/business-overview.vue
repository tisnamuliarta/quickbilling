<template>
  <v-row>
    <v-col cols="12" md="9" lg="9">
      <v-container>
        <v-row>
          <v-col cols="12">
            <v-row>
              <v-col cols="12" md="4">
                <v-card class="mx-auto">
                  <v-card-text>
                    <div class="subtitle-1">{{ $t('Sales Invoice') }}</div>
                  </v-card-text>
                  <v-card-text></v-card-text>
                </v-card>
              </v-col>

              <v-col cols="12" md="4">
                <v-card class="mx-auto">
                  <v-card-text>
                    <div class="subtitle-1">{{ $t('Expense') }}</div>
                  </v-card-text>
                  <v-card-text></v-card-text>
                </v-card>
              </v-col>

              <v-col cols="12" md="4">
                <v-card class="mx-auto">
                  <v-card-text>
                    <div class="subtitle-1">{{ $t('Profit and loss') }}</div>
                  </v-card-text>
                  <v-card-text></v-card-text>
                </v-card>
              </v-col>

              <v-col cols="12" md="4">
                <v-card class="mx-auto">
                  <v-card-text>
                    <div class="subtitle-1">{{ $t('Sales') }}</div>
                  </v-card-text>
                  <v-card-text>
                    <v-sheet color="rgba(0, 0, 0, .12)">
                      <v-sparkline
                        :value="value"
                        color="rgba(255, 255, 255, .7)"
                        height="100"
                        padding="24"
                        stroke-linecap="round"
                        smooth
                      >
                        <template v-slot:label="item">
                          ${{ item.value }}
                        </template>
                      </v-sparkline>
                    </v-sheet>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>
          </v-col>
        </v-row>
      </v-container>
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

  data() {
    return {
      value: [423, 446, 675, 510, 590, 610, 760],
    }
  },

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
