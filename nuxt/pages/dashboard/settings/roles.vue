<template>
  <v-layout>
    <v-flex sm12 md12>
      <div class="mt-0">
        <v-data-table
          :mobile-breakpoint="0"
          :headers="headers"
          :items="allData"
          :items-per-page="20"
          :options.sync="options"
          :server-items-length="totalData"
          :loading="loading"
          class="elevation-1"
          :footer-props="{ 'items-per-page-options': [20, 50, 100, -1] }"
        >
          <template v-slot:top>
            <v-toolbar flat color="white" dense>
              <v-toolbar-title class="hidden-xs-only">Master Roles</v-toolbar-title>
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

            <v-icon
              small
              class="mr-2"
              color="orange"
              @click="
                $refs.dialogPermission.openRolePermissions(
                  item,
                  'Role Permissions',
                  'role'
                )
              "
            >
              mdi-gate
            </v-icon>
          </template>
        </v-data-table>
      </div>
    </v-flex>

    <v-dialog v-model="dialog" persistent max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>
        <v-card-text>
          <v-form>
            <v-layout wrap>
              <v-flex v-if="message" xs12>
                <div class="red darken-2 text-xs-center">
                  <span class="white--text">{{ message }}</span>
                </div>
              </v-flex>

              <v-flex md12 class="d-flex">
                <v-layout wrap>
                  <v-flex md12 class="pa-2">
                    <v-text-field
                      v-model="form.name"
                      label="Role Name"
                      placeholder="Role Name"
                      outlined
                      dense
                      hide-details="auto"
                    ></v-text-field>
                  </v-flex>

                  <v-flex md12 class="pa-2">
                    <v-text-field
                      v-model="form.description"
                      label="Description"
                      placeholder="Description"
                      outlined
                      dense
                      hide-details="auto"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="red darken-1" text small @click="dialog = false">
            Close
          </v-btn>
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
    </v-dialog>

    <MasterDialogPermission ref="dialogPermission"></MasterDialogPermission>
  </v-layout>
</template>

<script>
export default {
  name: 'MasterRole',
  layout: 'dashboard',
  data() {
    return {
      totalData: 0,
      editedIndex: -1,
      loadNewBtn: false,
      submitLoad: false,
      dialogWindow: false,
      statusProcessing: 'insert',
      dialog: false,
      message: false,
      loading: true,
      insertDivision: true,
      insert: true,

      loadingPermission: true,
      allData: [],
      department: {},
      form: {
        id: null,
        name: null,
        description: null,
      },
      defaultItem: {
        id: null,
        name: null,
        description: null,
      },
      options: {},
      optionDivision: {},
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
  },

  methods: {
    getDataFromApi() {
      this.loading = true
      const vm = this
      this.$axios
        .get(`/api/master/roles`, {
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

    newData() {
      this.dialog = true
      this.statusProcessing = 'insert'
      this.form = Object.assign({}, this.defaultItem)
      this.message = false
    },

    editItem(item) {
      this.editedIndex = this.allData.indexOf(item)
      this.form = Object.assign({}, item)
      this.statusProcessing = 'update'
      this.dialog = true
      this.insert = false
    },

    close() {
      this.dialog = false
      this.statusProcessing = 'insert'
      setTimeout(() => {
        this.form = Object.assign({}, this.defaultItem)
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

      if (status === 'insert') {
        this.store('post', '/api/master/roles', data, 'insert', type)
        vm.submitLoad = false
      } else if (status === 'update') {
        this.store(
          'put',
          '/api/master/roles/' + this.form.id,
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
          if (res.data.status === 'Error') {
            this.$swal({
              type: 'error',
              title: 'Error',
              text: res.data.message,
            })
            vm.submitLoad = false
          } else {
            this.dialog = false
            this.message = res.data.message
            setTimeout(() => (this.message = false), 8000)

            this.getDataFromApi()
          }
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
