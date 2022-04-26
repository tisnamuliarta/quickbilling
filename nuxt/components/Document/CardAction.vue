<template>
  <v-timeline
    dense
  >
    <v-timeline-item
      v-for="item in action"
      :key="item.title"
      small
      :color="item.color"
    >
      <v-card class="elevation-2">
        <v-card-text class="pa-2">
          <span class="font-weight-bold">{{ item.title }}</span>
          <v-divider/>
          <div v-if="item.title !== 'Clone' && item.title !== 'Cancel'">
            <span>No : no</span> <br>
            <span>Date : no</span> <br>
            <span>Status : no</span> <br>
          </div>
        </v-card-text>
        <v-card-actions v-if="item.button">
          <v-btn
            :disabled="item.button"
            x-small
            :color="item.color"
            class="white--text"
            @click="processAction(item.action)"
          >
            <span v-if="item.title === 'Clone' || item.title === 'Cancel'" v-text="item.title"></span>
            <span v-else>Create</span>
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
      checkDisable: false,
      action: [],
    }
  },

  methods: {
    setAction(action, checkDisable) {
      this.checkDisable = checkDisable
      this.action = action
    },

    processAction(action) {

    }
  }
}
</script>
