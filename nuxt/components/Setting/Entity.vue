<template>
  <v-app-bar
    color="transparent"
    elevation="0"
  >
    <v-img  max-width="120" max-height="120" :src="logo"></v-img>
    <v-app-bar-title class="text-h5 ml-4">{{ companyName }}</v-app-bar-title>

    <v-spacer></v-spacer>

    <v-btn text small>Setup</v-btn>
  </v-app-bar>
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
      companyName: '',

      itemCurrency: [],
      logo: null,
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
          const url = res.data.data.url
          this.logo = url + '/files/logo/' + res.data.data.logo.value
          this.companyName = this.form.name
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
