<template>
  <v-row no-gutters align="center" align-content="center" justify="center">
    <v-col cols="12" sm="8" md="8" lg="7" xl="5" align-self="center">
      <v-skeleton-loader
        v-if="loadImage"
        type="article, actions"
        class="mx-auto"
      >
      </v-skeleton-loader>
      <v-form v-else @keyup.native.enter="login">
        <v-card class="mt-3 rounded-lg" elevation="0" rounded="lg" tile>
          <v-row no-gutters>
            <v-col cols="12" md="6">
              <v-card-text class="pb-0 mt-4">
                <v-row no-gutters>
                  <v-col cols="7">
                    <span class="text-h6">Sign In to your account</span> <br />
                    <span>Enter details below</span>
                  </v-col>
                  <v-col cols="5" class="text-right">
                    <img
                      :src="logo"
                      class="align-items-center justify-center logo"
                      alt="Logo"
                    />
                  </v-col>

                  <v-col cols="12">
                    <v-divider />
                  </v-col>

                  <v-col cols="12" class="mb-4 mt-4">
                    <v-text-field
                      v-model="form.username"
                      filled
                      label="Username"
                      required
                      hide-details="auto"
                    ></v-text-field>
                  </v-col>
                  <v-col cols="12" class="mb-4">
                    <v-text-field
                      v-model="form.password"
                      :append-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
                      :type="show ? 'text' : 'password'"
                      filled
                      label="Password"
                      required
                      hide-details="auto"
                      @click:append="show = !show"
                    ></v-text-field>
                  </v-col>

                  <v-col cols="12" class="mb-1">
                    <v-select
                      v-model="form.locale"
                      label="Language"
                      :items="language"
                      item-text="text"
                      item-value="value"
                      filled
                      hide-details
                    ></v-select>
                  </v-col>

                  <v-col cols="12" class="mb-1">
                    <v-checkbox
                      v-model="form.remember"
                      label="Remember Me"
                      color="success"
                      off-icon="mdi-checkbox-blank-outline"
                      on-icon="mdi-checkbox-marked"
                      hide-details
                    ></v-checkbox>
                  </v-col>
                </v-row>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="primary"
                  elevation="0"
                  block
                  class="mr-2"
                  :loading="loading"
                  @click="login"
                >
                  Sign In
                </v-btn>
              </v-card-actions>
            </v-col>

            <v-col cols="12" md="6" class="d-sm-none d-none d-md-flex">
              <v-divider vertical></v-divider>
              <v-img :src="bgLogin"></v-img>
            </v-col>
          </v-row>
        </v-card>
      </v-form>

      <v-snackbar
        v-model="snackbar"
        top
        multi-line
        transition="slide-y-transition"
      >
        {{ text }}

        <template #action="{ attrs }">
          <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
            Close
          </v-btn>
        </template>
      </v-snackbar>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'AuthLogin',
  layout: 'auth',
  middleware: 'guest',
  data() {
    return {
      form: {
        username: '',
        password: '',
        remember: false,
        locale: 'en',
        app_name: process.env.appName,
      },
      defaultForm: {
        username: '',
        password: '',
        remember: false,
        locale: 'en',
        app_name: process.env.appName,
      },
      language: [
        { text: 'English', value: 'en' },
        { text: 'Indonesia', value: 'id' },
      ],
      snackbar: false,
      text: '',
      appName: process.env.appName,
      select: null,
      show: false,
      loading: false,
      checkbox: null,
      loadImage: false,
      eye: true,
      remember: false,
      busy: false,
      message: '',
      logo: '',
      bgLogin: '',
      error: false,
      apps: [],
    }
  },

  head() {
    return {
      title: 'Login',
    }
  },

  mounted() {
    this.getLogo()
  },

  methods: {
    getLogo() {
      this.loadImage = true
      this.$axios.get(`/api/logo`).then((res) => {
        this.logo = res.data.data.default
        this.bgLogin = res.data.data.bgLogin
        this.loadImage = false
      })
    },

    clear() {
      this.form = Object.assign({}, this.defaultForm)
    },

    login() {
      this.loading = true
      this.$axios.get('/sanctum/csrf-cookie').then((res) => {
        this.$auth
          .loginWith('local', {
            data: this.form,
          })
          .then((response) => {
            this.loading = false
            this.$i18n.setLocale(this.form.locale)
            // this.$router.push('/dashboard')
          })
          .catch((err) => {
            this.loading = false
            this.snackbar = true
            this.text = err.response.data.message
            // this.$swal({
            //   type: 'error',
            //   title: 'Error',
            //   text: err.response.data.message,
            // })
          })
      })
    },
  },
}
</script>

<style scoped>
.logo {
  width: 150px;
  margin: 0 auto;
  text-align: center;
}
</style>
