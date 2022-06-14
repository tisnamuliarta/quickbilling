<template>
  <v-app-bar flat color="white" class="rounded" dense elevation="0">
    <!--    <v-toolbar-title class="hidden-xs-only subtitle-1 font-weight-bold">{{-->
    <!--      title-->
    <!--    }}</v-toolbar-title>-->
    <!--    <v-spacer></v-spacer>-->

    <!--    <v-dialog v-model="dialogFilter" persistent max-width="400px">-->
    <!--      <v-card>-->
    <!--        <v-card-title>Filter Form</v-card-title>-->
    <!--        <v-card-text>-->
    <!--          <TableFilter-->
    <!--            class="hidden-md-and-up"-->
    <!--            :document-status="documentStatus"-->
    <!--            :search-status="searchStatusData"-->
    <!--            :item-search="itemSearch"-->
    <!--            :search-item="searchItemData"-->
    <!--            :search="searchData"-->
    <!--            @passDataToToolbar="passDataToToolbar"-->
    <!--          ></TableFilter>-->
    <!--        </v-card-text>-->
    <!--        <v-card-actions>-->
    <!--          <v-spacer></v-spacer>-->
    <!--          <v-btn color="red darken-1" text small @click="dialogFilter = false">-->
    <!--            Close-->
    <!--          </v-btn>-->
    <!--        </v-card-actions>-->
    <!--      </v-card>-->
    <!--    </v-dialog>-->

    <v-btn v-if="showBatchAction" icon class="mr-0 pr-0">
      <v-icon>mdi-arrow-down-left</v-icon>
    </v-btn>

    <v-menu
      v-if="showBatchAction"
      transition="slide-y-transition"
      offset-y
      bottom
    >
      <template #activator="{ on, attrs }">
        <v-btn
          small
          color="black"
          class="ml-0 mr-2"
          dark
          outlined
          elevation="0"
          v-bind="attrs"
          v-on="on"
        >
          Batch Action
          <v-btn color="black" class="" dark small icon>
            <v-icon>mdi-menu-down</v-icon>
          </v-btn>
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-for="(value, i) in ['Make Inactive']"
          :key="i"
          dense
          @click="makeInActive(value, doctype)"
        >
          <v-list-item-content>
            <v-list-item-title>{{ value }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>

    <v-menu
      :close-on-content-click="false"
      :nudge-width="400"
      max-width="400px"
      bottom
      offset-y
    >
      <template #activator="{ on, attrs }">
        <v-btn
          v-if="showFilter"
          small
          outlined
          color="black"
          class="d-none d-sm-flex"
          elevation="0"
          dark
          v-bind="attrs"
          v-on="on"
        >
          Filter
          <v-btn dark color="black" class="d-none d-sm-flex" small icon>
            <v-icon>mdi-menu-down</v-icon>
          </v-btn>
        </v-btn>
      </template>

      <v-card>
        <v-card-text>
          <v-row dense>
            <v-col cols="12" md="6">
              <v-text-field
                label="Transaction"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>
            <v-col cols="12" md="6"> </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="Status"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="6">
              <v-text-field
                label="Delivery Method"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                label="Date"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                label="From"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <v-col cols="12" md="4">
              <v-text-field
                label="To"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>
          </v-row>
        </v-card-text>
        <v-card-actions>
          <v-btn text> Cancel </v-btn>
          <v-spacer></v-spacer>
          <v-btn color="primary" small elevation="0"> Apply </v-btn>
        </v-card-actions>
      </v-card>
    </v-menu>

    <v-form class="d-none d-sm-flex ml-2">
      <v-layout wrap>
        <v-row>
          <v-col cols="12" md="12" sm="12" class="mt-0 mr-2">
            <v-text-field
              v-model="searchData"
              @change="getDataFromApi"
              label="search"
              class="mt-1"
              outlined
              dense
              hide-details="auto"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-layout>
    </v-form>

    <v-spacer />

    <LazySetupBackList v-if="showBackLink"></LazySetupBackList>

    <v-btn
      v-if="showNewData"
      color="primary"
      class="d-none d-sm-flex"
      elevation="0"
      small
      @click="newData"
    >
      {{ newDataText }}
    </v-btn>

    <v-btn
      v-if="showNewData"
      color="primary"
      class="d-flex d-sm-none"
      elevation="0"
      small
      icon
      @click="newData"
    >
      <v-icon>mdi-plus-box</v-icon>
    </v-btn>

    <v-menu
      v-if="showNewDataMultiple"
      transition="slide-y-transition"
      offset-y
      bottom
    >
      <template #activator="{ on, attrs }">
        <v-btn small color="primary" elevation="0" v-bind="attrs" v-on="on">
          New Transactions
          <v-btn dark small icon>
            <v-icon>mdi-menu-down</v-icon>
          </v-btn>
        </v-btn>
      </template>
      <v-list dense>
        <v-list-item
          v-for="(value, i) in newDataMultipleItem"
          :key="i"
          dense
          @click="
            $router.push({
              path: value.route,
              query: {
                document: 0,
                type: value.type,
              },
            })
          "
        >
          <v-list-item-content>
            <v-list-item-title>{{ value.text }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-menu>

    <v-btn
      class="ml-1 mr-1 d-none d-sm-flex"
      :loading="loading"
      icon
      small
      @click="processAction('refresh')"
    >
      <v-icon>mdi-refresh</v-icon>
    </v-btn>

    <v-btn
      class="ml-1 mr-1 d-none d-sm-flex"
      :loading="loading"
      icon
      small
      @click="processAction('print')"
    >
      <v-icon>mdi-printer</v-icon>
    </v-btn>

    <v-btn
      class="ml-1 mr-1 d-none d-sm-flex"
      :loading="loading"
      icon
      small
      @click="processAction('export-excel')"
    >
      <v-icon>mdi-microsoft-excel</v-icon>
    </v-btn>

    <v-btn
      class="d-none d-sm-flex"
      :loading="loading"
      icon
      small
      @click="processAction('setting')"
    >
      <v-icon>mdi-cog</v-icon>
    </v-btn>

    <v-menu offset-y left class="d-flex d-sm-none" :nudge-width="120">
      <template v-slot:activator="{ on, attrs }">
        <v-btn icon v-bind="attrs" v-on="on" class="d-flex d-sm-none">
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </template>

      <v-list dense>
        <v-list-item
          link
          dense
          v-for="(item, i) in itemsMenu"
          :key="i"
          @click="processAction(item.action)"
        >
          <v-list-item-icon>
            <v-icon v-text="item.icon"></v-icon>
          </v-list-item-icon>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>
  </v-app-bar>
</template>

<script>
import TableFilter from './TableFilter'

export default {
  name: 'MainToolbar',

  components: {
    TableFilter,
  },

  props: {
    title: {
      type: String,
      default: '',
    },
    doctype: {
      type: String,
      default: '',
    },
    titleButton: {
      type: String,
      default: '',
    },
    searchItem: {
      type: String,
      default: '',
    },
    search: {
      type: String,
      default: '',
    },
    filters: {
      type: String,
      default: '',
    },
    searchStatus: {
      type: String,
      default: 'Active',
    },
    documentStatus: {
      type: Array,
      default() {
        return []
      },
    },
    buttonTitle: {
      type: String,
      default: 'New',
    },
    newDataText: {
      type: String,
      default: 'New',
    },
    showAdd: {
      type: Boolean,
      default: true,
    },
    filter: {
      type: Boolean,
      default: true,
    },
    showBatchAction: {
      type: Boolean,
      default: false,
    },
    showFilter: {
      type: Boolean,
      default: false,
    },
    showBackLink: {
      type: Boolean,
      default: false,
    },
    showNewData: {
      type: Boolean,
      default: false,
    },
    showNewDataMultiple: {
      type: Boolean,
      default: false,
    },
    newDataMultipleItem: {
      type: Array,
      default() {
        return []
      },
    },
    itemSearch: {
      type: Array,
      default() {
        return []
      },
    },
  },

  data() {
    return {
      loading: false,
      dialogFilter: false,
      searchStatusData: this.searchStatus,
      searchItemData: this.searchItem,
      searchData: this.search,
      items: [
        { text: 'Edit', action: 'edit' },
        { text: 'Delete', action: 'delete' },
      ],

      itemsMenu: [
        { title: 'Refresh', icon: 'mdi-refresh', action: 'refresh' },
        { title: 'Print', icon: 'mdi-printer', action: 'print' },
        {
          title: 'Export to excel',
          icon: 'mdi-microsoft-excel',
          action: 'export-excel',
        },
        { title: 'Form Settings', icon: 'mdi-cog', action: 'setting' },
      ],
    }
  },

  watch: {
    searchData: {
      handler() {
        this.$emit('emitData', {
          search: this.searchData,
        })
      },
      deep: true,
    },
  },

  methods: {
    newData() {
      this.$emit('newData')
    },

    passDataToToolbar(data) {
      this.$emit('emitData', {
        documentStatus: data.documentStatus,
        itemSearch: data.itemSearch,
        searchStatus: data.searchStatus,
        searchItem: data.searchItem,
        search: data.search,
      })
    },

    getDataFromApi() {
      this.$emit('getDataFromApi', {
        search: this.search,
      })
    },

    processAction(action) {
      switch (action) {
        case 'refresh':
          this.$emit('getDataFromApi')
          break
        case 'print':
          this.$emit('getDataFromApi')
          break
        case 'export-excel':
          this.$emit('getDataFromApi')
          break
        case 'setting':
          this.$emit('getDataFromApi')
          break
      }
    },

    makeInActive() {},
  },
}
</script>
