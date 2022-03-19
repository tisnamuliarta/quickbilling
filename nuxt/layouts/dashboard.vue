<template>
  <v-app>
    <v-app-bar
      app
      dense
      :color="dark ? undefined : 'white'"
      class="v-bar--underline v-toolbar-flat"
      flat
      clipped-left
    >
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
        <NuxtLink to="/">
          <v-skeleton-loader
            v-show="loadImage"
            type="avatar"
            class="mx-auto logo"
          >
          </v-skeleton-loader>
          <img
            v-show="!loadImage"
            :src="logo"
            class="mt-1"
            height="30"
          />
        </NuxtLink>
      </v-toolbar-title>

      <v-spacer></v-spacer>
      <v-tooltip bottom>
        <template #activator="{ on }">
          <v-btn icon class="mr-0" v-on="on">
            <v-icon size="25">mdi-bell</v-icon>
          </v-btn
          >
        </template>
        <span>Notifications</span>
      </v-tooltip>

      <v-menu offset-y left>
        <template #activator="{ on }">
          <v-btn
            x-small
            color="primary"
            depressed
            fab
            class="white--text"
            v-on="on"
          >
            {{ $auth.user.name.substring(0, 1) }}
          </v-btn>
        </template>

        <v-card>
          <v-list nav dense>
            <v-list-item>
              <v-list-item-content>
                <v-list-item-title>{{ $auth.user.name }}</v-list-item-title>
                <v-list-item-subtitle>{{
                    $auth.user.email
                  }}
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
          </v-list>

          <v-divider></v-divider>

          <v-list nav dense>
            <v-list-item router to="/studio">
              <v-list-item-icon>
                <v-icon>mdi-youtube-studio</v-icon>
              </v-list-item-icon>
              <v-list-item-title>Account Settings</v-list-item-title>
            </v-list-item>
            <v-list-item @click="logout">
              <v-list-item-icon>
                <v-icon>mdi-login-variant</v-icon>
              </v-list-item-icon>
              <v-list-item-title>Sign out</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-card>
      </v-menu>
    </v-app-bar>

    <v-navigation-drawer
      id="nav"
      v-model="drawer"
      app
      :clipped="$route.name !== 'Watch'"
      :temporary="$route.name === 'Watch'"
    >
      <v-list dense nav expand>
        <NuxtLink to="/" class="hidden-md-and-up">
          <v-skeleton-loader
            v-show="loadImage"
            type="avatar"
            class="mx-auto logo"
          >
          </v-skeleton-loader>
          <img
            v-show="!loadImage"
            :src="logo"
            class="mt-1"
            height="30"
          />
          <v-divider></v-divider>
        </NuxtLink>
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
        <Nuxt/>
      </v-container>
    </v-main>

    <v-footer color="grey lighten-3" padless>
      <v-col
        class="text-center"
        cols="12"
      >
        Copyright © {{ new Date().getFullYear() }} — <strong> TizCRM </strong>
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
      clipped: false,
      drawer: false,
      fixed: false,
      dark: undefined,
      items: [
        {
          menu: 'menu1',
          icon: 'mdi-receipt',
          children: [
            {menu: 'Home', route_name: '/', icon: 'mdi-home'},
            {menu: 'Trending', route_name: '/trending', icon: 'mdi-fire'},
            {
              menu: 'Subscriptions',
              route_name: '#s',
              icon: 'mdi-youtube-subscription',
            },
          ],
        },
      ],
      miniVariant: false,
      right: true,
      logo: '',
      rightDrawer: false,
      loadImage: false,
    }
  },

  mounted() {
    // enable using draggable dialogs
    this.activateMultipleDraggableDialogs()

    this.drawer = !this.$vuetify.breakpoint.mdAndDown
    this.drawer = this.$route.name === 'Watch' ? false : this.drawer
  },

  created() {
    this.menus()
    this.rolePermission()
    this.$nuxt.$on('getMenu', ($event) => this.menus($event))
    this.$nuxt.$on('getLogo', ($event) => this.getLogo($event))
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

    rolePermission() {
      this.$axios.post('/api/auth/roles').then((res) => {
        this.$gates.setRoles(res.data)
      })
      this.$axios.post('/api/auth/permissions').then((res) => {
        this.$gates.setPermissions(res.data)
      })
    },

    async logout() {
      await this.$auth.logout()
      this.$auth.$storage.removeLocalStorage('app.default_name')
      this.$auth.$storage.removeLocalStorage('employee')
      this.$auth.$storage.removeLocalStorage('country')

      localStorage.removeItem('roles')
      localStorage.removeItem('permissions')
      this.$router.push('/auth/login')
    },

    menus() {
      const appName = this.$auth.$storage.getLocalStorage('app.default_name')
      this.$axios.get(`/api/menus`, {
        params: {
          appName,
        },
      })
        .then(res => {
          this.items = res.data.data.menus
        })
        .catch((err) => {
          if (err.response.data.message === 'Call to a member function getAllPermissions() on null') {
            this.logout()
          }
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    changeDrawers(data) {
      this.$refs.navDrawer.changeDrawer(data)
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
