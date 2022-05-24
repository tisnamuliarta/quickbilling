<template>
  <v-row no-gutters align="center" align-content="center" justify="center">
    <v-col cols="12" sm="4" md="4" lg="4" xl="3" align-self="center">
      <v-skeleton-loader
        v-if="loadImage"
        type="article, actions"
        class="mx-auto"
      >
      </v-skeleton-loader>
      <v-form v-else @keyup.native.enter="login">
        <v-card
          class="mt-3"
          elevation="1"
          tile
        >
          <v-card-text>
            <v-row no-gutters>
              <v-col cols="7">
                <span class="text-h6">Sign In to your account</span> <br>
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
                  outlined
                  dense
                  label="Username"
                  required
                  hide-details="auto"
                ></v-text-field>
              </v-col>
              <v-col cols="12" class="mb-1">
                <v-text-field
                  v-model="form.password"
                  :append-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
                  :type="show ? 'text' : 'password'"
                  outlined
                  dense
                  label="Password"
                  required
                  hide-details="auto"
                  @click:append="show = !show"
                ></v-text-field>
              </v-col>
              <v-col cols="12" class="mb-3">
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
              class="mr-2"
              small
              :loading="loading"
              @click="login"
            >
              Sign In
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-form>
      <div v-if="error" class="red darken-2 text-xs-center pa-1">
        <span class="white--text">{{ message }}</span>
      </div>
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
        language: 'en',
        app_name: process.env.appName,
      },
      defaultForm: {
        username: '',
        password: '',
        remember: false,
        language: 'en',
        app_name: process.env.appName,
      },
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
      this.$axios.get(`/api/logo`)
        .then((res) => {
          this.logo = res.data.data.default
          this.loadImage = false
        })
    },

    clear() {
      this.form = Object.assign({}, this.defaultForm)
    },

    login() {
      this.loading = true
      this.$axios.get('/sanctum/csrf-cookie').then((res) => {
        this.$auth.loginWith('local', {
          data: this.form,
        })
          .then((response) => {
            this.loading = false
            // this.$router.push('/dashboard')
          })
          .catch((err) => {
            this.loading = false
            this.$swal({
              type: 'error',
              title: 'Error',
              text: err.response.data.message,
            })
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
