<template>
  <div>
    <v-menu offset-y left :nudge-width="700">
      <template #activator="{ on }">
        <v-btn
          color="primary"
          small
          icon
          v-on="on"
        >
          <v-icon>mdi-plus-circle</v-icon>
        </v-btn>
      </template>

      <v-card>
        <LazyFormNew ref="formNew" />
      </v-card>
    </v-menu>

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

    <v-menu offset-y left :nudge-width="700">
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
        <LazyFormSetting ref="formSetting" />
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
