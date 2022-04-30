<template>
  <div>
    <v-tooltip bottom>
      <template #activator="{ on }">
        <v-btn
          color="primary"
          small icon
          class="mr-2"
          v-on="on"
          @click="$emit('openDialog')"
        >
          <v-icon>mdi-plus-circle</v-icon>
        </v-btn
        >
      </template>
      <span>New</span>
    </v-tooltip>

    <v-tooltip bottom>
      <template #activator="{ on }">
        <v-btn small icon class="mr-2" v-on="on">
          <v-icon>mdi-bell</v-icon>
        </v-btn
        >
      </template>
      <span>Notifications</span>
    </v-tooltip>

    <v-tooltip bottom>
      <template #activator="{ on }">
        <v-btn small icon class="mr-2" v-on="on" @click="$router.push('/dashboard/settings/setup')">
          <v-icon>mdi-cog</v-icon>
        </v-btn
        >
      </template>
      <span>Settings</span>
    </v-tooltip>

    <v-menu offset-y left>
      <template #activator="{ on }">
        <v-btn
          x-small
          color="secondary"
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
  </div>
</template>

<script>
export default {
  name: "ToolBar",

  methods: {
    async logout() {
      await this.$auth.logout()
      this.$auth.$storage.removeLocalStorage('app.default_name')
      this.$auth.$storage.removeLocalStorage('employee')
      this.$auth.$storage.removeLocalStorage('country')

      localStorage.removeItem('roles')
      localStorage.removeItem('permissions')
      this.$router.push('/auth/login')
    },
  }
}
</script>
