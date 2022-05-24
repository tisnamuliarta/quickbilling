<template>
  <v-navigation-drawer
    id="nav"
    v-model="drawer"
    class="blue-grey darken-4"
    dark
    app
    :temporary="$route.name === 'dashboard-settings-setup'"
  >
    <v-list dense nav>
      <NuxtLink to="/">
        <v-skeleton-loader
          v-show="loadImage"
          type="avatar"
          class="mx-auto logo mb-3"
        >
        </v-skeleton-loader>
        <img v-show="!loadImage" :src="logo" class="mt-1 mb-3" height="50" />
        <v-divider></v-divider>
      </NuxtLink>

      <v-menu offset-y left :nudge-width="700">
        <template #activator="{ on }">
          <v-btn
            outlined
            block
            small
            rounded
            color="primary"
            class="mb-4"
            v-on="on"
          >
            <v-icon>mdi-plus</v-icon>
            New
          </v-btn>
        </template>

        <v-card>
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
</template>

<script>
export default {
  name: 'NavigationDrawer',

  props: {
    drawerData: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      items: {},
      loadImage: true,
      logo: '',
      drawer: this.drawerData,
    }
  },

  methods: {
    setItems(items) {
      this.items = items
    },

    setDrawer(drawer) {
      this.drawer = drawer
    },

    setLogo(logo) {
      this.loadImage = false
      this.logo = logo
    },

    openAction(data) {
      this.$emit('openAction', data)
    },
  },
}
</script>
