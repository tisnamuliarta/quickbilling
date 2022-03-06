<template>
  <v-layout>
    <v-flex sm12>
      <LazyMainToolbar
        :document-status="documentStatus"
        :search-status="searchStatus"
        :item-search="itemSearch"
        :search-item="searchItem"
        :search="search"
        title="Task Lists"
        @getDataFromApi="emitData"
        @newData="newData"
      />

      <div class="mt-2">
        <v-card>
          <v-tabs color="green accent-4" right dense>
            <v-tab>Landscape</v-tab>
            <v-tab>City</v-tab>
            <v-tab>Abstract</v-tab>

            <v-tab-item>
              <v-container fluid>
                <v-data-table
                  :mobile-breakpoint="0"
                  :headers="headers"
                  :items="allData"
                  :items-per-page="20"
                  :options.sync="options"
                  :server-items-length="totalData"
                  :loading="loading"
                  class="elevation-1"
                  :footer-props="{
                    'items-per-page-options': [20, 50, 100, -1],
                  }"
                >
                  <template #[`item.ACTIONS`]="{ item }">
                    <v-icon
                      small
                      class="mr-2"
                      color="orange"
                      @click="editItem(item)"
                    >
                      mdi-pencil-circle
                    </v-icon>

                    <v-icon
                      small
                      class="mr-2"
                      color="orange"
                      @click="
                        $refs.dialogPermission.openRolePermissions(
                          item,
                          'Role Permissions',
                          'role'
                        )
                      "
                    >
                      mdi-gate
                    </v-icon>
                  </template>
                </v-data-table>
              </v-container>
            </v-tab-item>
          </v-tabs>
        </v-card>
      </div>
    </v-flex>

    <LazyProductsFormProduct
      ref="formProduct"
      :form-data="form"
      :form-title="formTitle"
      :button-title="buttonTitle"
      @getDataFromApi="getDataFromApi"
    />

    <MasterDialogPermission ref="dialogPermission" />
  </v-layout>
</template>

<script>
export default {
  name: 'TaskDetails',
  layout: 'dashboard',
  data() {
    return {
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
        { text: 'Role Name', value: 'name' },
        { text: 'Description', value: 'description' },
        { text: 'Guard', value: 'guard_name' },
        { text: 'Action', value: 'ACTIONS', align: 'center' },
      ],
    }
  },

  head() {
    return {
      title: 'Tasks Details',
    }
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Task' : 'Edit Task'
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
      this.$refs.formProduct.newData()
    },

    editItem(item) {
      this.editedIndex = this.allData.indexOf(item)
      this.$refs.formProduct.editItem(item)
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
        .get(`/api/products/product`, {
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
