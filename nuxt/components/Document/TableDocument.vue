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
              :title="toolbarTitle"
              @emitData="emitData"
              @newData="newData"
            />
          </template>
          <template #[`item.document_number`]="{ item }">
            <a @click="editItem(item)" v-text="item.document_number"></a>
          </template>

          <template #[`item.status`]="{ item }">
            <v-btn
              text
              small
            >
              <v-icon :color="statusColor(item)" left>
                mdi-circle
              </v-icon>
              {{ item.status }}
            </v-btn>
          </template>

          <template #[`item.balance_due`]="{ item }">
            {{ form.default_currency_symbol + ' '+ $formatter.formatPrice(item.balance_due) }}
          </template>

          <template #[`item.amount`]="{ item }">
            {{ form.default_currency_symbol + ' '+ $formatter.formatPrice(item.amount) }}
          </template>
        </v-data-table>
      </div>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  name: "TableDocument",

  props: {
    typeDocument: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      totalData: 0,
      editedIndex: -1,
      loading: true,
      company: [],
      allData: [],
      documentStatus: [],
      itemSearch: [],
      toolbarTitle: '',
      searchStatus: '',
      searchItem: '',
      search: '',
      form: {},
      defaultItem: {},
      options: {},
      headers: [
        {text: 'Number', value: 'document_number'},
        {text: 'Customer', value: 'contact_name'},
        {text: 'Date', value: 'issued_at'},
        {text: 'Due Date', value: 'due_at'},
        {text: 'Status', value: 'status', align: 'center'},
        {text: 'Balance Due', value: 'balance_due', align: 'right'},
        {text: 'Total', value: 'amount', align: 'right'},
      ],
    }
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New ' + this.typeDocument : 'Edit ' + +this.typeDocument
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

  created() {
    this.mappingDocument()
  },

  methods: {
    newData() {
      this.$router.push({
        path: '/dashboard/documents/form',
        query: {
          document: this.form.id,
          type: this.form.type
        }
      })
    },

    statusColor(item) {
      switch (item.status) {
        case 'open':
        case 'partial':
          return 'warning'
        case 'paid':
          return 'green'
        case 'overdue':
          return 'red'
      }
    },

    editItem(item) {
      this.$router.push({
        path: '/dashboard/documents/form',
        query: {
          document: item.id,
          type: this.typeDocument
        }
      })
    },

    mappingDocument() {
      this.toolbarTitle = this.$helper.mapping(this.typeDocument)
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
        .get(`/api/documents/form`, {
          params: {
            options: vm.options,
            searchItem: vm.searchItem,
            documentStatus: vm.documentStatus,
            searchStatus: vm.searchStatus,
            search: vm.search,
            type: this.typeDocument,
          },
        })
        .then((res) => {
          this.loading = false
          this.allData = res.data.data.rows
          this.totalData = res.data.data.total
          this.itemSearch = res.data.filter
          this.form = Object.assign({}, res.data.data.form)
          this.defaultItem = Object.assign({}, res.data.data.form)
          this.company = this.$auth.$storage.getState('company')
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
