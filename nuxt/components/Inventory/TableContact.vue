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
          v-model="selected"
          v-show="!loading"
          :mobile-breakpoint="0"
          :headers="headers"
          :items="allData"
          :items-per-page="20"
          :options.sync="options"
          :server-items-length="totalData"
          :loading="loading"
          class="elevation-1"
          item-key="id"
          show-select
          dense
          :footer-props="{ 'items-per-page-options': [20, 50, 100, -1] }"
        >
          <template v-slot:top>
            <LazyMainToolbar
              :document-status="documentStatus"
              :search-status="searchStatus"
              :item-search="itemSearch"
              :search-item="searchItem"
              :search="search"
              title="Contacts"
              @emitData="emitData"
              @newData="newData"
            />
          </template>
          <template #[`item.ACTIONS`]="{ item }">
            <v-icon small class="mr-2" color="orange" @click="editItem(item)">
              mdi-pencil-circle
            </v-icon>
          </template>
          <template #[`item.balance`]="{ item }">
            {{ $formatter.formatPrice(item.balance) }}
          </template>
        </v-data-table>
      </div>
    </v-flex>

    <LazyInventoryFormContact
      ref="formData"
      :form-data="form"
      :form-title="formTitle"
      :button-title="buttonTitle"
      @getDataFromApi="getDataFromApi"
    />
  </v-layout>
</template>

<script>
export default {
  name: "TableContact",
  data() {
    return {
      selected: [],
      totalData: 0,
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
        { text: 'Name', value: 'name' },
        { text: 'Type', value: 'type' },
        { text: 'Company', value: 'company_name' },
        { text: 'Address', value: 'address' },
        { text: 'Email', value: 'email' },
        { text: 'Phone', value: 'phone' },
        { text: 'Balance', value: 'balance' },
        { text: 'Action', value: 'ACTIONS', align: 'center' },
      ],
    }
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Contacts' : 'Edit Contacts'
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

  methods: {
    newData() {
      this.editedIndex = -1
      this.$refs.formData.newData(this.form, this.defaultItem)
    },

    editItem(item) {
      this.editedIndex = this.allData.indexOf(item)
      this.$refs.formData.editItem(item)
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
        .get(`/api/inventory/contacts`, {
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
