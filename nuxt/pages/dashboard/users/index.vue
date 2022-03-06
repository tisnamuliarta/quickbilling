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
              title="Users"
              title-button="User"
              class="has-border-bottom"
              @emitData="emitData"
              @newData="newData"
            />
          </template>
          <template #[`item.Action`]="{ item }">
            <v-btn-toggle
              color="deep-blue accent-3"
              group
              dense
              tile
            >
              <v-btn value="left" @click="editItem(item)">
                <v-icon left>
                  mdi-pencil-circle
                </v-icon>
                <span class="hidden-sm-and-down">Edit</span>
              </v-btn>

              <v-btn value="center" @click="copyItem(item)">
                <v-icon left>
                  mdi-content-copy
                </v-icon>
                <span class="hidden-sm-and-down">Copy</span>
              </v-btn>

              <v-btn value="right" @click="
                      $refs.dialogPermission.openDialogPermission(
                        item,
                        'Direct Permissions'
                      )
                    ">
                <v-icon left>
                  mdi-playlist-edit
                </v-icon>
                <span class="hidden-sm-and-down">Permissions</span>
              </v-btn>
            </v-btn-toggle>
          </template>
        </v-data-table>
      </div>
    </v-flex>

    <DialogForm
      ref="dialogForm"
      max-width="700px"
      :dialog-title="formTitle"
      button-title="Save"
    >
      <template #content>
        <v-form>
          <v-layout wrap>
            <v-flex md12 class="d-flex">
              <v-layout wrap>
                <v-flex xs12 class="pa-2">
                  <v-text-field
                    v-model="form.name"
                    label="Name"
                    placeholder="Name"
                    outlined
                    dense
                    clearable
                    hide-details="auto"
                  >
                  </v-text-field>
                </v-flex>

                <v-flex xs12 class="pa-2">
                  <v-text-field
                    v-model="form.username"
                    label="Username"
                    placeholder="Username"
                    outlined
                    dense
                    clearable
                    hide-details="auto"
                  >
                  </v-text-field>
                </v-flex>

                <v-flex xs12 class="pa-2">
                  <v-text-field
                    v-model="form.email"
                    label="E-mail"
                    placeholder="E-mail"
                    outlined
                    dense
                    clearable
                    hide-details="auto"
                  >
                  </v-text-field>
                </v-flex>

                <v-flex xs12 class="pa-2">
                  <v-autocomplete
                    v-model="form.role"
                    :items="itemRole"
                    chips
                    deletable-chips
                    hide-no-data
                    small-chips
                    label="Role"
                    multiple
                    outlined
                    dense
                    hide-details="auto"
                  ></v-autocomplete>
                </v-flex>

                <v-flex xs12 class="pa-2">
                  <v-switch
                    v-model="form.enabled"
                    inset
                    label="Enabled"></v-switch>
                  <v-radio-group
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-form>
      </template>
      <template #saveData>
        <v-btn
          color="green darken-1"
          small
          dark
          :loading="submitLoad"
          @click="save()"
        >
          Save
        </v-btn>
      </template>
    </DialogForm>

    <MasterDialogPermission ref="dialogPermission"></MasterDialogPermission>
  </v-layout>
</template>

<script>
export default {
  name: 'MasterUser',
  layout: 'dashboard',
  data() {
    return {
      totalData: 0,
      editedIndex: -1,
      loadNewBtn: false,
      submitLoad: false,
      singleSelect: false,
      statusProcessing: 'insert',
      show: false,

      dialog: false,
      message: false,
      loading: true,
      loadingSync: false,

      searchItem: '',
      searchRole: '',
      search: '',
      searchStatus: '',

      allData: [],
      itemRole: [],
      itemApps: [],
      itemSearch: [],
      documentStatus: [],
      options: {},
      entries: [],
      dataUser: {},
      dataCompany: {},
      sub_id: {},
      searchUsername: null,
      form: {
        id: null,
        enabled: false,
        is_superuser: 'No',
        username: null,
        role: [],
        apps: [],
        division: [],
        whs: [],
        item_group: [],
        work_location: [],
        sub_role: [],
      },

      defaultForm: {
        id: null,
        is_superuser: 'No',
        enabled: false,
        username: null,
        role: [],
        apps: [],
        division: [],
        whs: [],
        item_group: [],
        work_location: [],
        sub_role: [],
      },

      headers: [
        {text: 'Username', value: 'username'},
        {text: 'Name', value: 'name'},
        {text: 'Email', value: 'email'},
        {text: 'Enabled', value: 'enabled'},
        {text: 'Role', value: 'role'},
        {text: 'Action', value: 'Action', align: 'end'},
      ],
    }
  },

  head() {
    return {
      title: 'Master Users',
    }
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1 ? 'New User' : 'Edit User'
    },
  },

  watch: {
    options: {
      handler() {
        this.getDataFromApi()
      },
      deep: true,
    },

    searchUsername(val) {
      this.getDataEmployee()
    },
  },

  mounted() {
    setTimeout(() => {
      this.refreshData()
    }, 500)
  },

  methods: {
    emitData(data) {
      this.documentStatus = data.documentStatus
      this.itemSearch = data.itemSearch
      this.searchStatus = data.searchStatus
      this.searchItem = data.searchItem
      this.search = data.search
      this.filters = data.filters
      this.getDataFromApi()
    },

    refreshData() {
      this.getDataFromApi()
      this.getRole()
    },

    getRole() {
      this.$axios
        .get(`/api/master/roles`)
        .then((res) => {
          this.itemRole = res.data.data.simple
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
        .get(`/api/master/users`, {
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
          this.allData = res.data.rows
          this.totalData = res.data.total
          this.itemSearch = res.data.filter
          this.itemWorkLocation = res.data.work_location
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

    newData() {
      this.$refs.dialogForm.openDialog()
      setTimeout(() => {
        this.form = Object.assign({}, this.defaultForm)
        this.editedIndex = -1
      }, 300)
      this.statusProcessing = 'insert'
    },

    editItem(item) {
      const dateForm = item
      dateForm.role = JSON.parse(dateForm.role)
      this.statusProcessing = 'update'
      this.editedIndex = this.allData.indexOf(item)
      this.form = Object.assign({}, dateForm)
      this.$refs.dialogForm.openDialog()
      this.dataUser = item
      this.insert = false
    },

    copyItem(item) {
      const dateForm = item
      dateForm.role = JSON.parse(dateForm.role)
      this.statusProcessing = 'copy'
      this.editedIndex = this.allData.indexOf(item)
      this.form = Object.assign({}, dateForm)
      this.$refs.dialogForm.openDialog()
      this.dataUser = item
      this.insert = false
    },

    close() {
      this.statusProcessing = 'insert'
      this.dialog = false
      setTimeout(() => {
        this.form = Object.assign({}, this.defaultForm)
        this.editedIndex = -1
      }, 300)
    },

    save(type = 'all', row = null) {
      const vm = this
      const form = this.form
      const status = this.statusProcessing
      const data = {
        form,
        status,
      }
      vm.submitLoad = true
      if (status === 'insert') {
        this.store('post', '/api/master/users', data, 'insert', type)
      } else if (status === 'copy') {
        this.store('post', '/api/master/users', data, 'insert', type)
      } else if (status === 'update') {
        this.store(
          'put',
          '/api/master/users/' + this.form.id,
          data,
          'update',
          type
        )
      }
    },

    store(method, url, data, type, column = 'all') {
      const vm = this
      vm.submitLoad = true
      this.$axios({method, url, data})
        .then((res) => {
          if (res.data.status === 'Error') {
            this.$swal({
              type: 'error',
              title: 'Error',
              text: res.data.message,
            })
          } else {
            this.dialog = false
            this.message = res.data.message
            setTimeout(() => (this.message = false), 8000)

            this.$swal({
              type: 'success',
              title: 'Success!',
              text: res.data.message,
            })
            this.$refs.dialogForm.closeDialog()
            this.getDataFromApi()
          }
          vm.submitLoad = false
        })
        // eslint-disable-next-line node/handle-callback-err
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
          vm.submitLoad = false
        })
    },
  },
}
</script>
