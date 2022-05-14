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
        {text: 'Account and Settings', type: 'SETTING', action: 'setting'},
        {text: 'Manage User', route: '/dashboard/users', action: 'page'},
        {
          text: 'Chart Of Account',
          route: '/dashboard/financial/account',
          action: 'page',
        },
      ],

      itemList: [
        {
          text: 'All List',
          route: '/dashboard/list',
          action: 'page',
        },
        {
          text: 'Product and Services',
          route: '/dashboard/inventory/item',
          action: 'page',
        },
        {
          text: 'Recurring Transactions',
          route: '/dashboard/list/recurring',
          action: 'page',
        },
        {
          text: 'Attachments',
          route: '/dashboard/list/attachment',
          action: 'page',
        },
        {
          text: 'Currencies',
          route: '/dashboard/financial/currency',
          action: 'page',
        },
      ],

      itemProfile: [
        {text: 'Account', route: '/dashboard/users/currenct', action: 'page'},
        {text: 'Sign Out', type: 'function', action: 'logout'},
      ],

      itemTool: [
        {
          text: 'Import Data',
          route: '/dashboard/tools/import',
          action: 'page',
        },
        {
          text: 'Export Data',
          route: '/dashboard/tools/export',
          action: 'page',
        },
        {
          text: 'Reconcile',
          route: '/dashboard/financial/reconcile',
          action: 'page',
        },
        {
          text: 'Budgeting',
          route: '/dashboard/financial/budgeting',
          action: 'page',
        },
        {text: 'Audit Log', route: '/dashboard/tools/audit', action: 'page'},
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
