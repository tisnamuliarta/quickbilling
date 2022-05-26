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
          color="green"
          class="ml-0 mr-2"
          dark
          elevation="0"
          v-bind="attrs"
          v-on="on"
        >
          Batch Action
          <v-btn dark small icon>
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

    <v-btn
      v-if="showFilter"
      small
      rounded
      color="green"
      dark
      @click="newData()"
    >
      Filter

      <v-menu
        :close-on-content-click="false"
        :nudge-width="400"
        max-width="400px"
        bottom
        offset-x
      >
        <template #activator="{ on, attrs }">
          <v-btn dark small icon v-bind="attrs" v-on="on">
            <v-icon>mdi-menu-down</v-icon>
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
            <v-btn color="primary" rounded> Apply </v-btn>
          </v-card-actions>
        </v-card>
      </v-menu>
    </v-btn>

    <TableFilter
      class="mr-2 ml-2"
      :document-status="documentStatus"
      :search-status="searchStatusData"
      :item-search="itemSearch"
      :search-item="searchItemData"
      :search="searchData"
      @passDataToToolbar="passDataToToolbar"
    ></TableFilter>

    <v-spacer />
    <v-btn :loading="loading" icon small @click="passDataToToolbar">
      <v-icon>mdi-refresh</v-icon>
    </v-btn>

    <v-btn :loading="loading" icon small @click="passDataToToolbar">
      <v-icon>mdi-printer-outline</v-icon>
    </v-btn>
    <v-btn :loading="loading" icon small @click="passDataToToolbar">
      <v-icon>mdi-microsoft-excel</v-icon>
    </v-btn>
    <v-btn :loading="loading" icon small @click="passDataToToolbar">
      <v-icon>mdi-cog-outline</v-icon>
    </v-btn>
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
    }
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

    makeInActive() {},
  },
}
</script>
