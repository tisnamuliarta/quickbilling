<template>
  <v-form @keyup.native.enter="getDataFromApi">
    <v-layout wrap>
      <v-row>
        <v-col cols="12" md="12" sm="12" class="mt-0 mr-2">
          <v-text-field
            v-model="searchData"
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
</template>

<script>
export default {
  name: 'TableFilter',
  props: {
    documentStatus: {
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
    searchStatus: {
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
  },

  data() {
    return {
      searchStatusData: this.searchStatus,
      searchItemData: this.searchItem,
      searchData: this.search,
    }
  },

  methods: {
    getDataFromApi() {
      this.$emit('passDataToToolbar', {
        documentStatus: this.documentStatus,
        itemSearch: this.itemSearch,
        searchStatus: this.searchStatusData,
        searchItem: this.searchItemData,
        search: this.searchData,
      })
    },
  },
}
</script>
