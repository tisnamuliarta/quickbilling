<template>
  <div class="text-center">
    <v-snackbar v-model="snackbar" bottom right :timeout="timeout">
      {{ text }}

      <template #action="{ attrs }">
        <v-btn color="red" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  name: 'Snackbar',
  data: () => ({
    snackbar: false,
    text: 'My timeout is set to 2000.',
    timeout: 2000,
  }),

  methods: {
    display(timeout = 8000, text) {
      this.snackbar = true
      this.timeout = timeout
      this.text = text
    },
  },
}
</script>
