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
        { text: 'Account and Settings', type: 'SETTING', action: 'setting' },
        { text: 'Manage User', type: '/dashboard/users/list', action: 'page' },
        {
          text: 'Chart Of Account',
          type: '/dashboard/financial/account',
          action: 'page',
        },
      ],

      itemList: [
        {
          text: 'All List',
          type: '/dashboard/transactions/list',
          action: 'page',
        },
        {
          text: 'Product and Services',
          type: '/dashboard/inventory/item',
          action: 'page',
        },
        {
          text: 'Recurring Transactions',
          type: '/dashboard/transactions/recurring',
          action: 'page',
        },
        {
          text: 'Attachments',
          type: '/dashboard/transactions/attachment',
          action: 'page',
        },
        {
          text: 'Currencies',
          type: '/dashboard/financial/currency',
          action: 'page',
        },
      ],

      itemProfile: [
        { text: 'Account', type: '/dashboard/users/currenct', action: 'page' },
        { text: 'Sign Out', type: 'logout', action: 'function' },
      ],

      itemTool: [
        {
          text: 'Import Data',
          type: '/dashboard/tools/import',
          action: 'page',
        },
        {
          text: 'Export Data',
          type: '/dashboard/tools/export',
          action: 'page',
        },
        {
          text: 'Reconcile',
          type: '/dashboard/financial/reconcile',
          action: 'page',
        },
        {
          text: 'Budgeting',
          type: '/dashboard/financial/budgeting',
          action: 'page',
        },
        { text: 'Audit Log', type: '/dashboard/tools/audit', action: 'page' },
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
