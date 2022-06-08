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
          class="font-weight-bold"
          v-text="companyName"
          @click="$router.push('/home/business-overview')"
          style="cursor: pointer"
        ></span>
      </v-toolbar-title>

      <v-spacer></v-spacer>

      <LazyLayoutToolBar ref="toolbar" @openAction="openAction" />

      <template v-if="showExtension" #extension>
        <v-tabs align-with-title>
          <v-tab
            v-for="(item, key) in extensionTabs"
            :key="key"
            @click="$router.push(item.route)"
          >
            {{ item.title }}</v-tab
          >
        </v-tabs>

        <v-spacer />

        <v-menu
          v-if="showExtensionButon"
          transition="slide-y-transition"
          offset-y
          bottom
        >
          <template #activator="{ on, attrs }">
            <v-btn
              small
              color="primary"
              elevation="0"
              v-bind="attrs"
              v-on="on"
            >
              New Transactions
              <v-btn dark small icon>
                <v-icon>mdi-menu-down</v-icon>
              </v-btn>
            </v-btn>
          </template>
          <v-list dense>
            <v-list-item
              v-for="(value, i) in extensionMenu"
              :key="i"
              dense
              @click="
                $router.push({
                  path: value.route,
                  query: {
                    document: 0,
                    type: value.type,
                  },
                })
              "
            >
              <v-list-item-content>
                <v-list-item-title>{{ value.text }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-menu>
      </template>
    </v-app-bar>

    <!-- <LazyLayoutNavigationDrawer
      ref="navDrawer"
      :drawer="drawer"
      @openAction="openAction"
    /> -->

    <v-navigation-drawer
      id="nav"
      v-model="drawer"
      class="blue-grey darken-4"
      dark
      app
      :temporary="$route.name === 'dashboard-settings-setup'"
    >
      <v-list dense nav>
        <NuxtLink to="/home/business-overview">
          <v-skeleton-loader
            v-show="loadImage"
            type="avatar"
            class="mx-auto logo mb-3"
          >
          </v-skeleton-loader>
          <img v-show="!loadImage" :src="logo" class="mt-1 mb-3" height="50" />
          <v-divider></v-divider>
        </NuxtLink>

        <v-menu
          transition="slide-y-transition"
          bottom
          offset-y
          left
          :nudge-width="700"
        >
          <template #activator="{ on }">
            <v-btn outlined block small color="primary" class="mb-4" v-on="on">
              <v-icon>mdi-plus</v-icon>
              New
            </v-btn>
          </template>

          <v-card class="rounded-lg" elevation="18">
            <LazyFormNew ref="formNew" @openAction="openAction" />
          </v-card>
        </v-menu>

        <v-list-group
          v-for="item in items"
          :key="item.menu"
          active-class="border"
          :prepend-icon="item.icon"
          append-icon="mdi-menu-down"
        >
          <template #activator>
            <v-list-item-content>
              <v-list-item-title v-text="item.menu"></v-list-item-title>
            </v-list-item-content>
          </template>

          <v-list-item
            v-for="(child, i) in item.children"
            :key="i"
            link
            :to="child.route_name"
            style="padding-left: 64px"
          >
            <v-list-item-content>
              <v-list-item-title v-text="child.menu"></v-list-item-title>
            </v-list-item-content>
            <v-list-item-icon v-if="child.icon">
              <v-icon v-text="child.icon"></v-icon>
            </v-list-item-icon>
          </v-list-item>
        </v-list-group>
      </v-list>
    </v-navigation-drawer>

    <v-main class="grey lighten-4">
      <v-container fluid>
        <Nuxt
          keep-alive
          :keep-alive-props="{ exclude: ['pages/sales/quote.vue'] }"
        />
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
      showExtension: false,
      showExtensionButon: false,
      extensionMenu: [],
      extensionTabs: '',
    }
  },

  mounted() {
    // enable using draggable dialogs
    // this.activateMultipleDraggableDialogs()

    this.drawer = !this.$vuetify.breakpoint.mdAndDown
    this.drawer =
      this.$route.name === 'dashboard-documents-form' ? false : this.drawer
    // this.changeDrawer()
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
    this.$nuxt.$on('extensionSetting', ($event) =>
      this.extensionSetting($event)
    )
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
          path: data.item.route,
          query: {
            document: 0,
            // type: data.item.type,
          },
        })
      } else {
        if (data.item.type === 'function') {
          this[data.item.action]()
        } else {
          this.$refs.settingForm.openDialog(data, 0, null)
        }
      }
    },

    extensionSetting(data) {
      this.showExtension = data.show
      this.showExtensionButon = data.showBtn
      this.extensionTabs = data.tabs
      this.extensionMenu = data.item
    },

    changeDrawer() {
      this.drawer = !this.drawer
      // this.$refs.navDrawer.setDrawer(this.drawer)
    },

    getLogo() {
      this.loadImage = true
      this.$axios.get(`/api/logo`).then((res) => {
        this.logo = res.data.data.logo
        // this.$refs.navDrawer.setLogo(this.logo)
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
          // this.$refs.navDrawer.setItems(this.items)
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
