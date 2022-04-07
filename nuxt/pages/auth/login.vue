<template>
  <v-row no-gutters align="center" align-content="center" justify="center">
    <v-col cols="12" sm="4" md="4" lg="4" xl="3" align-self="center">
      <v-skeleton-loader
        v-if="loadImage"
        type="article, actions"
        class="mx-auto"
      >
      </v-skeleton-loader>
      <v-card v-else class="mt-3" outlined elevation="0">
        <v-form @keyup.native.enter="login">
          <v-card-title primary-title>
            <img
              :src="logo"
              class="align-items-center justify-center logo"
              alt="Logo"
              @click="$router.push('/')"
            />
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-flex xs12 class="mb-3">
              <v-text-field
                v-model="form.username"
                outlined
                dense
                label="Username"
                required
                hide-details="auto"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 class="mb-1">
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
            </v-flex>
          </v-card-text>
          <v-card-actions>
            <v-flex class="text-right" xs12>
              <v-spacer></v-spacer>
              <v-btn small color="red" text @click="clear">clear</v-btn>
              <v-btn
                color="primary"
                class="mr-2"
                small
                :loading="loading"
                @click="login"
              >
                Login
                <v-icon right dark> mdi-login-variant</v-icon>
              </v-btn>
            </v-flex>
          </v-card-actions>
        </v-form>
      </v-card>
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
        language: 'en',
        app_name: process.env.appName,
      },
      defaultForm: {
        username: '',
        password: '',
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
        this.logo = res.data.data.logo
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
            this.$router.push('/dashboard')
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
  width: 100px;
  margin: 0 auto;
  text-align: center;
}
</style>
