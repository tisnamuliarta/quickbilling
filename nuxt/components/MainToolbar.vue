<template>
  <v-toolbar flat color="white" class="rounded" dense elevation="0">
    <v-toolbar-title class="hidden-xs-only subtitle-1 font-weight-bold">{{
      title
    }}</v-toolbar-title>
    <v-spacer></v-spacer>

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

    <TableFilter
      class="mr-2"
      :document-status="documentStatus"
      :search-status="searchStatusData"
      :item-search="itemSearch"
      :search-item="searchItemData"
      :search="searchData"
      @passDataToToolbar="passDataToToolbar"
    ></TableFilter>

    <v-btn v-if="showAdd" small color="green" dark @click="newData()">
      {{ buttonTitle }}
    </v-btn>

    <v-btn :loading="loading" icon @click="passDataToToolbar">
      <v-icon>mdi-refresh</v-icon>
    </v-btn>
  </v-toolbar>
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
      default: true
    },
    filter: {
      type: Boolean,
      default: true
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
  },
}
</script>
