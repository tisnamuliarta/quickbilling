<template>
  <v-layout>
    <v-flex sm12>
      <div class="mt-0">
        <v-alert
          border="top"
          elevation="1"
          class="pr-0 pl-0"
          colored-border
          color="green lighten-2"
        >
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
            class="elevation-0"
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
                :button-title="btnTitle"
                @emitData="emitData"
                @newData="newData"
              />
            </template>
            <template #[`item.document_number`]="{ item }">
              <a @click="editItem(item)">
                <strong v-text="item.document_number"></strong>
              </a>
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
              {{ form.default_currency_symbol + ' ' + $formatter.formatPrice(item.balance_due) }}
            </template>

            <template #[`item.amount`]="{ item }">
              {{ form.default_currency_symbol + ' ' + $formatter.formatPrice(item.amount) }}
            </template>

            <template #[`item.actions`]="{ item }">
              <v-btn
                color="secondary"
                class="font-weight-bold text-right pr-0"
                text
                small
                @click="actions(itemAction, item)"
              >
                {{ itemText }}
              </v-btn>
              <v-menu
                transition="slide-y-transition"
                bottom
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    color="black"
                    dark
                    icon
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-icon>mdi-menu-down</v-icon>
                  </v-btn>
                </template>
                <v-list>
                  <v-list-item
                    v-for="(value, i) in items"
                    :key="i"
                    @click="actions(value.action, item)"
                  >
                    <v-list-item-content>
                      <v-list-item-title>{{ value.text }}</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
              </v-menu>
            </template>
          </v-data-table>
        </v-alert>
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
    btnTitle: {
      type: String,
      default: 'New Transaction',
    },
    items: {
      type: Array,
      default() {
        return [
          {text: 'Edit', action: 'edit'},
          {text: 'Delete', action: 'delete'},
        ]
      }
    },
    headerTable: {
      type: Array,
      default() {
        return []
      }
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
      itemText: '',
      itemAction: '',
      headers: this.headerTable,
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

  mounted() {
    this.itemText = this.items[0].text
    this.itemAction = this.items[0].action
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
        case 'closed':
          return 'green'
        case 'overdue':
          return 'red'
        case 'cancel':
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

    actions(action, item) {
      if (action === 'edit') {
        this.editItem(item)
      } else {
        this.deleteItem(item)
      }
    },

    deleteItem(item) {
      this.$axios.delete(`/api/master/permissions/` + item.menu_name)
        .then(res => {
          this.getDataFromApi()
          this.$nuxt.$emit('getMenu', 'nice payload')
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
