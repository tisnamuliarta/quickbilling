<template>
  <v-dialog
    v-model="dialogPermission"
    persistent
    max-width="1000px"
    transition="dialog-bottom-transition"
  >
    <v-card :loading="loadingPermission">
      <v-card-title>
        <span class="subtitle-2">
          {{ dialogTitle }}
        </span>
        <v-spacer></v-spacer>
        <v-btn
          color="red darken-1"
          dark
          icon
          small
          @click="closeDialogPermission"
        >
          <v-icon>mdi-close-circle</v-icon>
        </v-btn>
      </v-card-title>
      <v-card-text>
        <div class="scroll-container">
          <LazyMasterPermissionList
            ref="childDetails"
          ></LazyMasterPermissionList>
        </div>
      </v-card-text>
      <v-card-actions>
        <v-btn
          v-if="canAddData"
          small
          color="orange darken-1"
          class="white--text"
          @click="$refs.childDetails.addLine()"
        >
          Add Line
        </v-btn>
        <v-spacer></v-spacer>
        <v-btn
          v-if="canAddData"
          color="green darken-1"
          dark
          small
          :loading="loadingPermission"
          @click="saveRolePermission()"
        >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'DialogPermission',
  data() {
    return {
      loadingPermission: false,
      dialogPermission: false,
      dialogTitle: '',
      role: '',
      userRole: [],
      form: {},
      canAddData: false,
      type: '',
    }
  },

  mounted() {
    this.getPermissionList()
  },

  methods: {
    getPermissionList() {
      this.$axios.get(`/api/master/permissions`).then((res) => {
        this.$auth.$storage.setLocalStorage(
          'permission_list',
          res.data.data.simple
        )
      })
    },
    // Open dialog for user permissions
    openDialogPermission(item, title) {
      this.dialogTitle = title
      this.dialogPermission = true
      this.loadingPermission = true
      this.form = Object.assign({}, item)
      this.canAddData = true
      this.getPermissionUser(item)
      this.type = 'user'
    },

    // get permission by current user
    getPermissionUser(item) {
      this.userRole = null
      this.$axios
        .get(`/api/master/user/permission`, {
          params: {
            form: item,
          },
        })
        .then((res) => {
          this.$refs.childDetails.setDataToDetails(res.data.data.rows)
        })
        .finally((res) => {
          this.loadingPermission = false
        })
    },

    closeDialogPermission() {
      this.dialogPermission = false
      this.userRole = []
      this.canAddData = false
    },
    // get current user roles
    openRolePermissions(item, title, type) {
      const vm = this
      this.type = type
      this.dialogTitle = title
      this.dialogPermission = true
      this.loadingPermission = true
      this.form = Object.assign({}, item)

      let url = ''
      if (type === 'user') {
        url = '/api/master/user/roles'
      } else {
        this.canAddData = true
        url = '/api/master/permission-role'
      }
      this.$axios
        .get(url, {
          params: {
            form: this.form,
          },
        })
        .then((res) => {
          vm.$refs.childDetails.setDataToDetails(res.data.data.rows)
          this.loadingPermission = false
        })
        .finally((res) => {
          this.loadingPermission = false
        })
    },
    // change tab for user role
    changeTab(val) {
      this.loadingPermission = true
      this.$axios
        .post(`/api/master/permission-role`, {
          form: val,
        })
        .then((res) => {
          this.$refs.childDetails.setDataToDetails(res.data.data.rows)
        })
        .finally((res) => {
          this.loadingPermission = false
        })
    },

    getRolePermissionUser(item) {
      this.$axios
        .post(`/api/master/user/role-permission`, {
          item,
        })
        .then((res) => {
          this.$refs.childDetails.setDataToDetails(res.data.data.rows)
        })
        .finally((res) => {
          this.loadingPermission = false
        })
    },

    getPermissionRole(item) {
      this.$axios
        .post(`/api/master/permission-role`, {
          form: this.form,
        })
        .then((res) => {
          this.$refs.childDetails.setDataToDetails(res.data.data.rows)
        })
        .finally((res) => {
          this.loadingPermission = false
        })
    },

    saveRolePermission() {
      this.loadingPermission = true
      const details = this.$refs.childDetails.getAddData()
      let url = ''
      if (this.type === 'user') {
        url = '/api/master/user/permission'
      } else {
        url = '/api/master/permission-role'
      }

      this.$axios
        .post(url, {
          details,
          form: this.form,
        })
        .then((res) => {
          this.$swal({
            icon: 'success',
            title: 'Success',
            text: res.data.message,
          })

          if (this.type === 'user') {
            this.getPermissionUser(this.form)
          } else {
            this.getPermissionRole(this.form)
          }
        })
        .catch((err) => {
          this.$swal({
            icon: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
        .finally((res) => {
          this.loadingPermission = false
        })
    },
  },
}
</script>
