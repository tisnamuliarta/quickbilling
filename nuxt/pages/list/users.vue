<template>
  <v-layout>
    <v-flex sm12>
      <div class="mt-0">
        </v-skeleton-loader>
        <v-data-table
          :mobile-breakpoint="0"
          :headers="headers"
          :items="allData"
          :items-per-page="20"
          :options.sync="options"
          :server-items-length="totalData"
          :loading="loading"
          :footer-props="{ 'items-per-page-options': [20, 50, 100, -1] }"
          class="elevation-1"
          show-select
          dense
          fixed-header
          height="70vh"
        >
          <template #top>
            <div class="pl-4 pt-2">
              <span class="font-weight-medium text-h6">Users</span>
            </div>

            <LazyMainToolbar
              :document-status="documentStatus"
              :search-status="searchStatus"
              :item-search="itemSearch"
              :search-item="searchItem"
              :search="search"
              :filter="false"
              title="Users"
              title-button="User"
              class="has-border-bottom"
              show-back-link
              show-new-data
              show-batch-action
              new-data-text="New User"
              @emitData="emitData"
              @newData="newData"
              @getDataFromApi="getDataFromApi"
            />
          </template>
          <template #[`item.Action`]="{ item }">
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
                  <v-select
                    v-model="form.entity_id"
                    :items="itemEntity"
                    hide-no-data
                    label="Company"
                    outlined
                    dense
                    item-text="name"
                    item-value="id"
                    hide-details="auto"
                    clearable
                  ></v-select>
                </v-flex>

                <v-flex xs12 class="pa-2">
                  <v-switch
                    v-model="form.enabled"
                    inset
                    label="Enabled"
                  ></v-switch>
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
  data() {
    return {
      totalData: 0,
      editedIndex: -1,
      loadNewBtn: false,
      submitLoad: false,
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
      items: [
        { text: 'Edit', action: 'edit' },
        { text: 'Copy', action: 'copy' },
        { text: 'Direct Permissions', action: 'permissions' },
      ],
      itemText: '',
      itemAction: '',

      allData: [],
      itemRole: [],
      itemEntity: {},
      itemSearch: [],
      documentStatus: [],
      options: {},
      sub_id: {},
      url: '',
      form: {},
      defaultForm: {},

      headers: [
        { text: 'Username', value: 'username' },
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Enabled', value: 'enabled' },
        { text: 'Role', value: 'role' },
        { text: 'Action', value: 'Action', align: 'end' },
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
  },

  activated() {
    setTimeout(() => {
      this.refreshData()
    }, 500)

    this.itemText = this.items[0].text
    this.itemAction = this.items[0].action
  },

  methods: {
    changeEntity() {},

    actions(action, item) {
      if (action === 'edit') {
        this.editItem(item)
      } else if (action === 'copy') {
        this.copyItem(item)
      } else {
        this.$refs.dialogPermission.openDialogPermission(
          item,
          'Direct Permissions'
        )
      }
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

    refreshData() {
      this.getDataFromApi()
      this.getRole()
      this.getEntity()
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

    getEntity() {
      this.$axios
        .get(`/api/entities`)
        .then((res) => {
          this.itemEntity = res.data.data.simple
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
          this.allData = res.data.data.rows
          this.totalData = res.data.data.total
          this.itemSearch = res.data.data.filter
          this.form = Object.assign({}, res.data.data.form)
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
      this.insert = false
    },

    copyItem(item) {
      const dateForm = item
      dateForm.role = JSON.parse(dateForm.role)
      this.statusProcessing = 'copy'
      this.editedIndex = this.allData.indexOf(item)
      this.form = Object.assign({}, dateForm)
      this.$refs.dialogForm.openDialog()
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
      vm.submitLoad = true
      if (status === 'insert') {
        this.store('post', '/api/master/users', form, 'insert', type)
      } else if (status === 'copy') {
        this.store('post', '/api/master/users', form, 'insert', type)
      } else if (status === 'update') {
        this.store(
          'put',
          '/api/master/users/' + this.form.id,
          form,
          'update',
          type
        )
      }
    },

    store(method, url, data, type, column = 'all') {
      const vm = this
      vm.submitLoad = true
      this.$axios({ method, url, data })
        .then((res) => {
          this.dialog = false
          this.$swal({
            type: 'success',
            title: 'Success!',
            text: res.data.message,
          })
          this.$refs.dialogForm.closeDialog()
          this.getDataFromApi()
          vm.submitLoad = false
        })
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
