<template>
  <v-footer dark padless color="light-blue darken-3">
    <v-container>
      <v-row justify="center" no-gutters>
        <v-col cols="12" class="mx-xs-auto">
          <v-card flat tile class="light-blue darken-3 white--text text-center">
            <v-card-text>
              <v-btn
                v-for="icon in icons"
                :key="icon"
                class="mx-4 white--text"
                icon
              >
                <v-icon size="24px">
                  {{ icon }}
                </v-icon>
              </v-btn>
            </v-card-text>

            <v-card-text class="white--text pt-0">
              <v-container>
                <v-row>
                  <v-col cols="12" sm="3" md="3" lg="3" class="mx-xs-auto">
                    <v-list dense color="transparent" class="text-left">
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </v-col>

                  <v-col cols="12" sm="3" md="3" lg="3" class="mx-xs-auto">
                    <v-list dense color="transparent" class="text-left">
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </v-col>

                  <v-col cols="12" sm="3" md="3" lg="3" class="mx-xs-auto">
                    <v-list dense color="transparent" class="text-left">
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </v-col>

                  <v-col cols="12" sm="3" md="3" lg="3" class="mx-xs-auto">
                    <v-list dense color="transparent" class="text-left">
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                      <v-list-item>
                        <v-list-item-content>
                          <v-list-item-title>Menu 1</v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-text class="white--text">
              {{ new Date().getFullYear() }} — <strong>Vuetify</strong>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </v-footer>
</template>

<script>
export default {
  name: 'FooterMain',
  data: () => ({
    icons: ['mdi-facebook', 'mdi-twitter', 'mdi-linkedin', 'mdi-instagram'],
  }),
}
</script>

<style scoped></style>
