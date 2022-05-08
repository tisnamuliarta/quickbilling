<template>
  <v-app>
    <v-app-bar
      app
      :color="dark ? undefined : 'white'"
      class="v-bar--underline v-toolbar-flat"
      flat
    >
      <v-app-bar-nav-icon @click="changeDrawer()"></v-app-bar-nav-icon>

      <v-toolbar-title class="ml-0 pl-0">
        <span
          class="font-weight-bold hidden-sm-and-down"
          v-text="companyName"
          @click="$router.push('/dashboard')"
          style="cursor: pointer"
        ></span>
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <LazyLayoutToolBar ref="toolbar" @openAction="openAction" />
    </v-app-bar>

    <LazyLayoutNavigationDrawer
      ref="navDrawer"
      :drawer="drawer"
      @openAction="openAction"
    />

    <v-main class="grey lighten-4">
      <v-container fluid>
        <Nuxt />
      </v-container>
    </v-main>

    <v-snackbar v-model="snackbar" top color="primary" right elevation="24">
      {{ message }}
      <template v-slot:action="{ attrs }">
        <v-btn color="pink" small icon v-bind="attrs" @click="snackbar = false">
          <v-icon>mdi-close-thick</v-icon>
        </v-btn>
      </template>
    </v-snackbar>

    <LazyFormCheckForm ref="checkForm" />

    <LazySetupListSetting ref="settingForm" />

    <v-footer color="grey lighten-3" padless>
      <v-col class="text-center" cols="12">
        Copyright © {{ new Date().getFullYear() }} —
        <strong> {{ company.company_name }} </strong>
      </v-col>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  name: 'DefaultLayout',
  middleware: 'auth',
  data() {
    return {
      snackbar: false,
      clipped: false,
      drawer: false,
      fixed: false,
      dark: undefined,
      message: '',
      items: [],
      company: [],
      miniVariant: false,
      right: true,
      logo: '',
      rightDrawer: false,
      loadImage: false,
      companyName: '',
    }
  },

  mounted() {
    // enable using draggable dialogs
    // this.activateMultipleDraggableDialogs()

    this.drawer = !this.$vuetify.breakpoint.mdAndDown
    this.drawer =
      this.$route.name === 'dashboard-documents-form' ? false : this.drawer
    this.$refs.navDrawer.setDrawer(this.drawer)
  },

  created() {
    this.menus()
    this.rolePermission()
    this.getCompany()
    this.$nuxt.$on('getMenu', ($event) => this.menus($event))
    this.$nuxt.$on('getLogo', ($event) => this.getLogo($event))
    this.$nuxt.$on('getCompany', ($event) => this.getCompany($event))
    this.$nuxt.$on('snackbar', ($event) => this.openSnackbar($event))
    this.$nuxt.$on('openSetting', ($event) => this.openSetting($event))
    this.getLogo()
  },

  methods: {
    openSnackbar(data) {
      if (data) {
        this.snackbar = true
        this.message = data
      }
    },

    openSetting(data) {
      this.$refs.settingForm.openDialog(data, 0, null)
    },

    openAction(data) {
      if (data.item.route) {
        this.$router.push({
          path: data.item.route
        })
      } else {
        if (data.item.type === 'function') {
          this[data.item.action]()
        } else {
          this.$refs.settingForm.openDialog(data, 0, null)
        }
      }
      // switch (data.item.action) {
      //   case 'page':
      //     this.$route.push({ page: data.item.type })
      //     break
      //   case 'function':
      //     this[data.item.type]()
      //     break
      //   case 'document':
      //   case 'transaction':
      //     this.$refs.checkForm.openDialog(data, 0, null)
      //     break
      //   case 'setting':
      //     this.$refs.settingForm.openDialog(data, 0, null)
      //     break
      //
      //   default:
      //     break
      // }
    },

    changeDrawer() {
      this.drawer = !this.drawer
      this.$refs.navDrawer.setDrawer(this.drawer)
    },

    getLogo() {
      this.loadImage = true
      this.$axios.get(`/api/logo`).then((res) => {
        this.logo = res.data.data.logo
        this.$refs.navDrawer.setLogo(this.logo)
        this.loadImage = false
      })
    },

    getCompany() {
      this.$axios
        .get(`/api/settings`, {
          params: {
            page: 'company',
          },
        })
        .then((res) => {
          this.$auth.$storage.setState('company', res.data.data.form)
          this.company = this.$auth.$storage.getState('company')
          this.companyName = res.data.data.form.company_name
        })
    },

    async logout() {
      await this.$auth.logout()
      this.$auth.$storage.removeLocalStorage('app.default_name')
      this.$auth.$storage.removeLocalStorage('employee')
      this.$auth.$storage.removeLocalStorage('country')

      localStorage.removeItem('roles')
      localStorage.removeItem('permissions')
    },

    rolePermission() {
      this.$axios.post('/api/auth/roles').then((res) => {
        this.$gates.setRoles(res.data)
      })
      this.$axios.post('/api/auth/permissions').then((res) => {
        this.$gates.setPermissions(res.data)
      })
    },

    menus() {
      const appName = this.$auth.$storage.getLocalStorage('app.default_name')
      this.$axios
        .get(`/api/menus`, {
          params: {
            appName,
          },
        })
        .then((res) => {
          this.items = res.data.data.menus
          this.$refs.navDrawer.setItems(this.items)
        })
        .catch((err) => {
          if (
            err.response.data.message ===
            'Call to a member function getAllPermissions() on null'
          ) {
            this.logout()
          }
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },
  },
}
</script>

<style scoped>
.v-toolbar-flat {
  box-shadow: 0 1px 0 0 rgb(0 0 0 / 20%), 0 0 0 0 rgb(0 0 0 / 14%),
    0 0 0 0 rgb(0 0 0 / 12%) !important;
}
</style>
