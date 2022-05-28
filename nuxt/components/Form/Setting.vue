<template>
  <v-container fluid>
    <v-row no-gutters>
      <v-col cols="12" md="3">
        <LazyFormListAction
          title="YOUR COMPANY"
          :items="itemCompany"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>

      <v-col cols="12" md="3">
        <LazyFormListAction
          title="LISTS"
          :items="itemList"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>

      <v-col cols="12" md="3">
        <LazyFormListAction
          title="TOOLS"
          :items="itemTool"
          @openAction="openAction"
        ></LazyFormListAction>
      </v-col>

      <v-col cols="12" md="3">
        <LazyFormListAction
          title="PROFILE"
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
        { text: 'Manage User', route: '/users', action: 'page' },
        {
          text: 'Chart Of Account',
          route: '/financial/account',
          action: 'page',
        },
      ],

      itemList: [
        {
          text: 'All List',
          route: '/list',
          action: 'page',
        },
        {
          text: 'Product and Services',
          route: '/inventory/item',
          action: 'page',
        },
        {
          text: 'Recurring Transactions',
          route: '/list/recurring',
          action: 'page',
        },
        {
          text: 'Attachments',
          route: '/list/attachment',
          action: 'page',
        },
        {
          text: 'Currencies',
          route: '/financial/currency',
          action: 'page',
        },
        { text: 'Tags', type: 'page', action: 'page' },
      ],

      itemProfile: [
        { text: 'Account', route: '/users/currenct', action: 'page' },
        { text: 'Sign Out', type: 'function', action: 'logout' },
      ],

      itemTool: [
        { text: 'Reclasify transaction', route: '/users/currenct', action: 'page' },
        {
          text: 'Import Data',
          route: '/tools/import',
          action: 'page',
        },
        {
          text: 'Export Data',
          route: '/tools/export',
          action: 'page',
        },
        {
          text: 'Reconcile',
          route: '/financial/reconcile',
          action: 'page',
        },
        {
          text: 'Budgeting',
          route: '/financial/budgeting',
          action: 'page',
        },
        { text: 'Audit Log', route: '/tools/audit', action: 'page' },
        { text: 'Backup Company', route: '/tools/audit', action: 'page' },
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
