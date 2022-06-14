<template>
  <v-layout>
    <v-flex sm12>
      <div class="mt-0">
        <!--        <v-skeleton-loader-->
        <!--          v-show="loading"-->
        <!--          type="table"-->
        <!--          class="mx-auto"-->
        <!--        >-->
        <!--        </v-skeleton-loader>-->
        <v-data-table
          :mobile-breakpoint="0"
          :headers="headers"
          :items="allData"
          :items-per-page="20"
          :options.sync="options"
          :server-items-length="totalData"
          :loading="loading"
          hide-default-footer
          class="elevation-1"
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
              title="Account Category"
              @emitData="emitData"
              @newData="newData"
            />
          </template>
          <template #[`item.id`]="{ item }">
            <v-btn
              text
              small
              @click="editItem(item)"
              color="secondary"
              class="font-weight-bold text-right"
              >Edit</v-btn
            >
          </template>
        </v-data-table>
      </div>
    </v-flex>

    <LazyAccountingFormAccountCategory
      ref="forms"
      :form-data="form"
      :form-title="formTitle"
      :button-title="buttonTitle"
      :url="url"
      @getDataFromApi="getDataFromApi"
    ></LazyAccountingFormAccountCategory>
  </v-layout>
</template>

<script>
export default {
  name: 'AccountCategory',
  data() {
    return {
      totalData: 0,
      editedIndex: -1,
      loading: true,
      allData: [],
      documentStatus: [],
      itemSearch: [],
      itemCategoryType: [],
      searchStatus: '',
      searchItem: '',
      search: '',
      form: {},
      defaultItem: {},
      options: {},
      url: '/api/financial/account-category',
      headers: [
        { text: 'Category Name', value: 'name' },
        { text: 'Category Type', value: 'category_type' },
        { text: 'Actions', value: 'id' },
      ],
    }
  },

  head() {
    return {
      title: 'Account Category',
    }
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New Category' : 'Edit Category'
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
      this.$refs.forms.newData(this.form, this.itemCategoryType)
    },

    editItem(item) {
      this.editedIndex = this.allData.indexOf(item)
      this.$refs.forms.editItem(item, this.itemCategoryType)
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
        .get(this.url, {
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
          this.itemCategoryType = res.data.data.category_type_list
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
