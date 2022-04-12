<template>
  <v-layout>
    <v-skeleton-loader
      v-show="loading"
      type="card-heading, article, actions"
      max-width="100%"
      class="mx-auto"
    >
    </v-skeleton-loader>
    <v-card v-show="!loading">
      <v-card-title>
        Company
        <v-spacer/>
        <v-btn :loading="loading" icon @click="refreshData">
          <v-icon>mdi-refresh</v-icon>
        </v-btn>
      </v-card-title>
      <v-divider />
      <v-container>
        <v-row no-gutters>
          <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-text-field
              v-model="form.name"
              label="Name"
              outlined
              dense
              hide-details="auto"
            ></v-text-field>
          </v-col>
          <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-select
              v-model="form.currency_id"
              :items="itemCurrency"
              item-value="id"
              item-text="name"
              clearable
              label="Currency"
              outlined
              dense
              hide-details="auto"
            ></v-select>
          </v-col>
          <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-checkbox
              v-model="form.multi_currency"
              dense
              hide-details
              label="Multi Currency"
              class="mt-0"
            ></v-checkbox>
          </v-col>
          <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-checkbox
              v-model="form.mid_year_balances"
              dense
              hide-details
              label="Mid Year Balances"
              class="mt-0"
            ></v-checkbox>
          </v-col>
          <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
            <v-text-field
              v-model="form.year_start"
              dense
              hide-details
              outlined
              label="Year Start"
              class="mt-0"
              type="number"
            ></v-text-field>
          </v-col>
        </v-row>
      </v-container>
      <v-divider />
      <v-card-actions>
        <v-spacer/>
        <v-btn
          color="green darken-1"
          dark
          small
          :loading="submitLoad"
          @click="save()"
        >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-layout>
</template>

<script>
export default {
  name: 'Entity',
  data() {
    return {
      totalData: 0,
      editedIndex: -1,
      submitLoad: false,
      statusProcessing: 'insert',
      dialog: false,
      loading: true,
      insert: true,
      url: '/api/entities',

      itemCurrency: [],
      allData: [],
      form: {},
      defaultItem: {},
      options: {},
      headers: [
        {text: 'Name', value: 'name'},
        {text: 'Currency', value: 'currency'},
        {text: 'Year Start', value: 'year_start'},
        {text: 'Action', value: 'ACTIONS', align: 'center'},
      ],
    }
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New' : 'Edit'
    },
  },

  mounted() {
    this.refreshData()
  },

  methods: {
    refreshData() {
      this.getDataFromApi()
      this.getCurrency()
    },

    getCurrency() {
      this.$axios.get(`/api/financial/currency`)
        .then(res => {
          this.itemCurrency = res.data.data.rows
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    getDataFromApi() {
      this.loading = true
      const vm = this
      this.$axios
        .get(this.url, {
          params: {
            options: vm.options,
          },
        })
        .then((res) => {
          this.loading = false
          this.allData = res.data.data.rows
          this.totalData = res.data.data.total
          this.statusProcessing = res.data.data.status
          this.form = Object.assign({}, res.data.data.rows)
          this.defaultForm = Object.assign({}, res.data.data.form)
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

    save(type = 'all', row = null) {
      const vm = this
      const form = this.form
      const status = this.statusProcessing

      if (status === 'insert') {
        this.store('post', this.url, form, 'insert', type)
        vm.submitLoad = false
      } else if (status === 'update') {
        this.store(
          'put',
          this.url + '/' + this.form.id,
          form,
          'update',
          type
        )
        vm.submitLoad = false
      }
    },

    store(method, url, data, type, column = 'all') {
      const vm = this
      vm.submitLoad = true
      this.$axios({method, url, data})
        .then((res) => {
          vm.submitLoad = false
          this.$swal({
            type: 'success',
            title: 'Success',
            text: res.data.message,
          })
          this.getDataFromApi()
        })
        .catch((err) => {
          vm.submitLoad = false
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
