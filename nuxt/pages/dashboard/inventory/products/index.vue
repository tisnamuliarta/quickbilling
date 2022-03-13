<template>
  <v-layout>
    <v-flex sm12>
      <div class="mt-0">
        <v-skeleton-loader
          v-show="loading"
          type="table"
          class="mx-auto"
        >
        </v-skeleton-loader>
        <v-data-table
          v-show="!loading"
          :mobile-breakpoint="0"
          :headers="headers"
          :items="allData"
          :items-per-page="20"
          :options.sync="options"
          :server-items-length="totalData"
          :loading="loading"
          class="elevation-1"
          :footer-props="{ 'items-per-page-options': [20, 50, 100, -1] }"
        >
          <template v-slot:top>
            <LazyMainToolbar
              :document-status="documentStatus"
              :search-status="searchStatus"
              :item-search="itemSearch"
              :search-item="searchItem"
              :search="search"
              title="Products"
              @emitData="emitData"
              @newData="newData"
            />
          </template>
          <template #[`item.ACTIONS`]="{ item }">
            <v-icon small class="mr-2" color="orange" @click="editItem(item)">
              mdi-pencil-circle
            </v-icon>
          </template>
        </v-data-table>
      </div>
    </v-flex>

    <LazyInventoryFormItem
      ref="formData"
      :form-data="form"
      :form-title="formTitle"
      :button-title="buttonTitle"
      @getDataFromApi="getDataFromApi"
    ></LazyInventoryFormItem>
  </v-layout>
</template>

<script>
export default {
  name: 'Products',
  layout: 'dashboard',
  data() {
    return {
      totalData: 0,
      url: '',
      editedIndex: -1,
      loading: true,
      allData: [],
      documentStatus: [],
      itemSearch: [],
      searchStatus: '',
      searchItem: '',
      search: '',
      form: {},
      defaultItem: {},
      options: {},
      headers: [
        { text: 'Product Code', value: 'code' },
        { text: 'Product Name', value: 'name' },
        { text: 'Product Category', value: 'type' },
        { text: 'Minimum Stock', value: 'minimum_stock' },
        { text: 'Unit', value: 'unit' },
        { text: 'Average Price', value: 'average_price' },
        { text: 'Last Buy Price', value: 'last_buy_price' },
        { text: 'Buy Price', value: 'purchase_price' },
        { text: 'Sell Price', value: 'sale_price' },
        { text: 'Action', value: 'ACTIONS', align: 'center' },
      ],
    }
  },

  head() {
    return {
      title: 'Products',
    }
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },
    buttonTitle() {
      return this.editedIndex === -1 ? 'Save' : 'Update'
    },
  },

  watch: {
    options: {
      handler() {
        this.getDataFromApi()
      },
      deep: true,
    },
  },

  mounted() {
    this.getDataFromApi()
  },

  methods: {
    newData() {
      this.editedIndex = -1
      this.$refs.formData.newData()
    },

    editItem(item) {
      this.editedIndex = this.allData.indexOf(item)
      this.$refs.formData.editItem(item, this.url)
    },

    emitData(data) {
      this.documentStatus = data.documentStatus
      this.itemSearch = data.itemSearch
      this.searchStatus = data.searchStatus
      this.searchItem = data.searchItem
      this.search = data.search
      this.filters = data.filters
      this.getDataFromApi()
    },

    getDataFromApi() {
      this.loading = true
      const vm = this
      this.$axios
        .get(`/api/inventory/items`, {
          params: {
            options: vm.options,
            searchItem: vm.searchItem,
            documentStatus: vm.documentStatus,
            searchStatus: vm.searchStatus,
            search: vm.search,
          },
        })
        .then((res) => {
          this.loading = false
          this.allData = res.data.data.rows
          this.totalData = res.data.data.total
          this.itemSearch = res.data.filter
          this.form = Object.assign({}, res.data.data.form)
          this.defaultItem = Object.assign({}, res.data.data.form)
          this.url = res.data.data.url
        })
        .catch((err) => {
          this.loading = false
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },
  },
}
</script>
