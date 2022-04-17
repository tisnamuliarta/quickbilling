<template>
  <v-timeline
    dense
  >
    <v-timeline-item
      v-for="item in action"
      :key="item.title"
      small
    >
      <v-card class="elevation-2">
        <v-card-text class="pa-2">
          <span class="font-weight-bold">{{ item.title }}</span>
          <v-divider />
          <div>
            {{ item.action }}
          </div>
        </v-card-text>
        <v-card-actions>
          <v-btn x-small color="primary">
            Create
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-timeline-item>
  </v-timeline>
</template>

<script>
export default {
  name: "CardAction",
  props: {
    title: {
      type: String,
      default: ''
    },
  },

  data() {
    return {
      loading: true,
      action: [],
    }
  },

  methods: {
    setAction(action) {
      this.action = action
    }
  }
}
</script>
