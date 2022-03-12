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
            <v-toolbar flat color="white" dense>
              <v-toolbar-title class="hidden-xs-only">Taxes</v-toolbar-title>
              <v-divider class="mx-2" inset vertical></v-divider>
              <v-spacer></v-spacer>
              <v-btn icon color="green" dark @click="newData()">
                <v-icon>mdi-plus-circle</v-icon>
              </v-btn>

              <v-btn :loading="loading" icon @click="getDataFromApi">
                <v-icon>mdi-refresh</v-icon>
              </v-btn>
            </v-toolbar>
          </template>
          <template #[`item.ACTIONS`]="{ item }">
            <v-icon small class="mr-2" color="orange" @click="editItem(item)">
              mdi-pencil-circle
            </v-icon>
          </template>
        </v-data-table>
      </div>
    </v-flex>

    <DialogForm
      ref="dialogForm"
      max-width="600px"
      :dialog-title="formTitle"
      button-title="Save"
    >
      <template #content>
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
              <vuetify-money
                v-model="form.rate"
                v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                v-bind:options="moneyOptions"
                label="Rate"
                outlined
                dense
                hide-details="auto"
              ></vuetify-money>
            </v-col>

            <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-autocomplete
                v-model="form.sell_account"
                :items="itemAccounts"
                item-text="name"
                item-value="id"
                label="Sell Tax Account"
                outlined
                dense
                hide-details="auto"
              ></v-autocomplete>
            </v-col>

            <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-autocomplete
                v-model="form.buy_account"
                :items="itemAccounts"
                item-text="name"
                item-value="id"
                label="Buy Tax Account"
                outlined
                dense
                hide-details="auto"
              ></v-autocomplete>
            </v-col>

            <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-sheet>
                <v-switch
                  v-model="form.enabled"
                  inset
                  label="Enabled"
                  hide-details
                ></v-switch>
              </v-sheet>
            </v-col>

            <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-sheet>
                <v-switch
                  v-model="form.withholding"
                  inset
                  label="Withholding"
                  hide-details
                ></v-switch>
              </v-sheet>
            </v-col>
          </v-row>
        </v-container>
      </template>
      <template #saveData>
        <v-btn
          color="green darken-1"
          dark
          small
          :loading="submitLoad"
          @click="save()"
        >
          Save
        </v-btn>
      </template>
    </DialogForm>
  </v-layout>
</template>

<script>
export default {
  name: 'Taxes',
  layout: 'dashboard',
  data() {
    return {
      totalData: 0,
      editedIndex: -1,
      submitLoad: false,
      statusProcessing: 'insert',
      dialog: false,
      loading: true,
      insert: true,
      url: '/api/financial/taxes',

      valueWhenIsEmpty: '0',
      moneyOptions: {
        suffix: "",
        length: 11,
        precision: 2
      },

      itemAccounts: [],
      allData: [],
      form: {},
      defaultItem: {},
      options: {},
      headers: [
        { text: 'Name', value: 'name' },
        { text: 'Rate', value: 'rate' },
        { text: 'Withholding', value: 'withholding' },
        { text: 'Sell Tax Account', value: 'sell_account_name' },
        { text: 'Buy Tax Account', value: 'buy_account_name' },
        { text: 'Action', value: 'ACTIONS', align: 'center' },
      ],
    }
  },

  head() {
    return {
      title: 'Master Roles',
    }
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New' : 'Edit'
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
    this.getAccounts()
  },

  methods: {
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

    getAccounts() {
      this.$axios.get(`/api/financial/accounts`, {
        params: {
          type: "All"
        }
      })
        .then((res) => {
          this.itemAccounts = res.data.data.rows
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    newData() {
      this.$refs.dialogForm.openDialog()
      this.statusProcessing = 'insert'
      this.form = Object.assign({}, this.defaultItem)
    },

    editItem(item) {
      this.editedIndex = this.allData.indexOf(item)
      this.form = Object.assign({}, item)
      this.statusProcessing = 'update'
      this.$refs.dialogForm.openDialog()
      this.insert = false
    },

    save(type = 'all', row = null) {
      const vm = this
      const form = this.form
      const status = this.statusProcessing
      const data = {
        form,
        status,
      }

      if (status === 'insert') {
        this.store('post', this.url, data, 'insert', type)
        vm.submitLoad = false
      } else if (status === 'update') {
        this.store(
          'put',
          this.url + '/' + this.form.id,
          data,
          'update',
          type
        )
        vm.submitLoad = false
      }
    },

    store(method, url, data, type, column = 'all') {
      const vm = this
      vm.submitLoad = true
      this.$axios({ method, url, data })
        .then((res) => {
          vm.submitLoad = false
          this.$refs.dialogForm.closeDialog()
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
