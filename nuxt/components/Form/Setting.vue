<template>
  <v-container fluid>
    <v-row no-gutters>
      <v-col cols="12" md="3">
        <LazyFormListAction
          title="Your Company"
          :items="itemCompany"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>

      <v-col cols="12" md="3">
        <LazyFormListAction
          title="Lists"
          :items="itemList"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>

      <v-col cols="12" md="3">
        <LazyFormListAction
          title="Tools"
          :items="itemTool"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>

      <v-col cols="12" md="3">
        <LazyFormListAction
          title="Profile"
          :items="itemProfile"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'Setting',

  data() {
    return {
      dialog: false,
      itemCompany: [
        { text: 'Account and Settings', type: 'SETTING', action: 'document' },
        { text: 'Manage User', type: 'USER', action: 'action' },
        { text: 'Chart Of Account', type: 'COA', action: 'document' },
      ],

      itemList: [
        { text: 'All List', type: 'LIST', action: 'document' },
        { text: 'Product and Services', type: 'PRODUCT', action: 'document' },
        {
          text: 'Recurring Transactions',
          type: 'RECCURING',
          action: 'document',
        },
        { text: 'Attachments', type: 'ATTACHMENT', action: 'document' },
        { text: 'Currencies', type: 'CURRENCY', action: 'document' },
      ],

      itemProfile: [
        { text: 'Account', type: 'ACCOUNT', action: 'action' },
        { text: 'Sign Out', type: 'SIGNOUT', action: 'action' },
      ],

      itemTool: [
        { text: 'Import Data', type: 'IMPORT', action: 'transaction' },
        { text: 'Export Data', type: 'EXPORT', action: 'transaction' },
        { text: 'Reconcile', type: 'RECONCILE', action: 'transaction' },
        { text: 'Budgeting', type: 'BUDGET', action: 'transaction' },
        { text: 'Audit Log', type: 'AUDIT', action: 'transaction' },
      ],
    }
  },

  methods: {
    openDialog() {
      this.dialog = true
    },

    openAction(data) {
      this.$emit('openAction', {
        item: data.item,
      })
      this.dialog = false
    },
  },
}
</script>
