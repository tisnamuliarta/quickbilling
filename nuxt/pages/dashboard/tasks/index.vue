<template>
  <div>
    <v-toolbar color="grey lighten-1" extended flat></v-toolbar>

    <v-card flat class="mx-auto" max-width="95%" style="margin-top: -64px">
      <v-toolbar flat>
        <v-toolbar-title class="black--text"> Workspace </v-toolbar-title>

        <v-spacer></v-spacer>

        <v-btn icon>
          <v-icon>mdi-magnify</v-icon>
        </v-btn>

        <v-btn icon>
          <v-icon>mdi-apps</v-icon>
        </v-btn>

        <v-btn icon color="green" dark @click="newData()">
          <v-icon>mdi-plus-circle</v-icon>
        </v-btn>

        <v-btn icon>
          <v-icon>mdi-dots-vertical</v-icon>
        </v-btn>
      </v-toolbar>

      <v-divider></v-divider>

      <v-card-text style="height: 200px">
        <v-layout wrap>
          <v-row>
            <v-col
              v-for="n in 4"
              :key="n"
              cols="12"
              sm="2"
              md="2"
              @click="$router.push('/dashboard/tasks/show')"
            >
              <v-card elevation="0" color="green">
                <v-card-title>
                  <span class="subtitle-1 white--text">IT HARDWARE</span>
                </v-card-title>
                <v-card-actions>
                  <v-list-item class="grow">
                    <v-row align="center" justify="end">
                      <v-icon class="mr-1"> mdi-heart </v-icon>
                    </v-row>
                  </v-list-item>
                </v-card-actions>
              </v-card>
            </v-col>
          </v-row>
        </v-layout>
      </v-card-text>
    </v-card>

    <LazyTasksFormTask
      ref="formTask"
      :form-data="form"
      :form-title="formTitle"
      :button-title="buttonTitle"
    />
  </div>
</template>

<script>
export default {
  name: 'MasterRole',
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
      title: 'Tasks',
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
      this.$refs.formTask.newData()
    },

    editItem(item) {
      this.editedIndex = this.allData.indexOf(item)
      this.$refs.formTask.editItem(item)
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
