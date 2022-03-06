<template>
  <v-app>
    <v-app-bar
      app
      :color="dark ? undefined : 'white'"
      class="v-bar--underline"
      flat
      clipped-left
    >
      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title style="width: 300px" class="ml-0 pl-3">
        <NuxtLink to="/">
          <img
            src="~/assets/images/tizapps.svg"
            class="mt-1"
            height="30"
            alt="E-KB"
          />
        </NuxtLink>
      </v-toolbar-title>

      <v-spacer></v-spacer>
      <v-toolbar-items class="hidden-xs-only mt-8">
        <div v-for="parentItem in items" :key="parentItem.header">
          <v-btn
            v-for="item in parentItem.pages"
            :key="item.title"
            text
            small
            :to="item.link"
          >
            {{ item.title }}
          </v-btn>
        </div>
      </v-toolbar-items>
      <v-toolbar-items v-if="$auth.loggedIn" class="hidden-xs-only mt-8">
        <div>
          <v-btn small text to="/dashboard">Dashboard</v-btn>
        </div>
      </v-toolbar-items>

      <v-toolbar-items v-if="!$auth.loggedIn" class="hidden-xs-only mt-8">
        <div>
          <v-btn small text to="/auth/login">Sign In</v-btn>
        </div>
      </v-toolbar-items>
    </v-app-bar>

    <v-navigation-drawer
      id="nav"
      v-model="drawer"
      app
      :clipped="$route.name !== 'Watch'"
      :temporary="$route.name === 'Watch'"
    >
      <div class="v-navigation-drawer__content pt-2">
        <v-list dense nav class="py-0" tag="div">
          <v-list-item
            :class="{
              'hidden-lg-and-up': $route.name === 'Watch' ? false : true,
            }"
          >
            <v-app-bar-nav-icon
              class="mr-5"
              @click="drawer = !drawer"
            ></v-app-bar-nav-icon>
            <v-toolbar-title class="font-weight-bold">VueTube</v-toolbar-title>
          </v-list-item>
          <v-divider class="hidden-lg-and-up"></v-divider>
          <div v-for="parentItem in items" :key="parentItem.header">
            <v-subheader
              v-if="parentItem.header"
              class="pl-3 py-4 subtitle-1 font-weight-bold text-uppercase"
              >{{ parentItem.header }}
            </v-subheader>
            <v-list-item
              v-for="(item, i) in parentItem.pages"
              :key="item.title"
              link
              class="mb-0"
              router
              :to="item.link"
              exact
              active-class="active-item"
            >
              <v-list-item-icon v-if="parentItem.header !== 'Subscriptions'">
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-item-icon>
              <v-list-item-avatar v-else class="mr-5">
                <img
                  :src="`https://randomuser.me/api/portraits/men/${i}.jpg`"
                />
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title class="font-weight-medium subtitle-2"
                  >{{ item.title }}
                </v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-divider class="mt-2 mb-2"></v-divider>
          </div>
        </v-list>
      </div>
    </v-navigation-drawer>

    <v-main class="grey lighten-4">
      <Nuxt />
    </v-main>

    <LazyFooter></LazyFooter>
  </v-app>
</template>

<script>
export default {
  name: 'DefaultLayout',
  middleware: 'verification',
  data() {
    return {
      clipped: false,
      drawer: false,
      fixed: false,
      dark: undefined,
      items: [
        {
          header: null,
          pages: [
            {
              title: 'Library',
              link: '#l',
              icon: 'mdi-play-box-multiple',
            },
            {
              title: 'History',
              link: '/history',
              icon: 'mdi-history',
            },
            {
              title: 'Your videos',
              link: '/channels/ddd',
              icon: 'mdi-play-box-outline',
            },

            {
              title: 'Watch later',
              link: '#wl',
              icon: 'mdi-clock',
            },

            {
              title: 'Liked videos',
              link: '#lw',
              icon: 'mdi-thumb-up',
            },
          ],
        },
      ],
      miniVariant: false,
      right: true,
      rightDrawer: false,
      title: 'Vuetify.js',
    }
  },

  mounted() {
    // enable using draggable dialogs
    this.activateMultipleDraggableDialogs()

    // this.drawer = !this.$vuetify.breakpoint.mdAndDown
    // this.drawer = this.$route.name === 'Watch' ? false : this.drawer
  },

  created() {
    // this.menus()
    // this.rolePermission()
  },

  methods: {
    rolePermission() {
      this.$axios.get('/api/auth/roles').then((res) => {
        this.$gates.setRoles(res.data)
      })
      this.$axios.get('/api/auth/permissions').then((res) => {
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
      this.$router.push('/login')
    },

    async menus() {
      const appName = this.$auth.$storage.getLocalStorage('app.default_name')
      const menu = await this.$axios.get(`/api/menus`, { params: { appName } })
      this.items = menu.data.data.menus
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
