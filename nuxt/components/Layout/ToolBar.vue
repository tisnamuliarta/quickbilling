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
        <LazyFormNew ref="formNew" @openAction="openAction" />
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

    <!-- <v-tooltip bottom>
      <template #activator="{ on }">
        <v-btn small icon class="mr-2" v-on="on" @click="$router.push('/dashboard/settings/setup')">
          <v-icon>mdi-cog</v-icon>
        </v-btn
        >
      </template>
      <span>Settings</span>
    </v-tooltip> -->

    <v-menu offset-y left :nudge-width="700">
      <template #activator="{ on }">
        <v-btn
          small
          icon
          v-on="on"
        >
          <v-icon>mdi-cog</v-icon>
        </v-btn>
      </template>

      <v-card>
        <LazyFormSetting ref="formSetting" @openAction="openAction" />
      </v-card>
    </v-menu>
  </div>
</template>

<script>
export default {
  name: "ToolBar",

  data() {
    return {
      username: ''
    }
  },

  mounted() {
    this.username = this.$auth.user.name.substring(0, 1)
  },

  methods: {
    openAction(data) {
      this.$emit('openAction', data)
    },
  }
}
</script>
