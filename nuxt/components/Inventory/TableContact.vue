<template>
  <v-layout>
    <v-flex sm12>
      <div class="mt-0">
        <v-data-table
          v-model="selected"
          :mobile-breakpoint="0"
          :headers="headers"
          :items="allData"
          :items-per-page="20"
          :options.sync="options"
          :server-items-length="totalData"
          :loading="loading"
          class="elevation-0"
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
              show-batch-action
              show-new-data
              new-data-text="New Customer"
              @emitData="emitData"
              @newData="newData"
            />
          </template>
          <template #[`item.ACTIONS`]="{ item }">
            <v-btn
              color="secondary"
              class="font-weight-bold text-right"
              text
              small
              @click="actions(itemAction, item)"
            >
              {{ itemText }}
            </v-btn>
            <v-menu transition="slide-y-transition" bottom>
              <template #activator="{ on, attrs }">
                <v-btn color="black" dark icon v-bind="attrs" v-on="on">
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
  name: 'TableContact',
  props: {
    contactType: {
      type: String,
      default: 'Customer',
    },
  },
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
        {text: 'Name', value: 'name'},
        {text: 'Type', value: 'type'},
        {text: 'Company', value: 'company_name'},
        {
          text: 'Email', value: 'email',
          sortable: false,
          filterable: false,
        },
        {
          text: 'Phone', value: 'phone',
          sortable: false,
          filterable: false,
        },
        {
          text: 'Balance', value: 'balance',
          sortable: false,
          filterable: false,
        },
        {
          text: 'Action', value: 'ACTIONS',
          align: 'center',
          sortable: false,
          filterable: false,
        },
      ],
      items: [
        {text: 'Edit', action: 'edit'},
        {text: 'Delete', action: 'delete'},
      ],
      itemText: '',
      itemAction: '',
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

  mounted() {
    this.itemText = this.items[0].text
    this.itemAction = this.items[0].action
  },

  methods: {
    newData() {
      this.editedIndex = -1
      this.$refs.formData.newData(this.form, this.defaultItem)
    },

    actions(action, item) {
      if (action === 'edit') {
        this.editItem(item)
      } else {
        this.deleteItem(item)
      }
    },
    editItem(item) {
      this.$router.push({
        path: '/app/contact/form-customer',
        query: {
          document: item.id
        },
      })
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
        .get(`/api/bp/contacts`, {
          params: {
            options: vm.options,
            searchItem: vm.searchItem,
            documentStatus: vm.documentStatus,
            searchStatus: vm.searchStatus,
            search: vm.search,
            contactType: vm.contactType
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
