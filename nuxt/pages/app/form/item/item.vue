<template>
  <v-dialog
    v-model="dialog"
    max-width="700px"
    persistent
    transition="dialog-top-transition"
    scrollable
  >
    <v-card tile>
      <v-card-title>
        <v-toolbar-title>
          <v-btn icon>
            <v-icon>mdi-progress-pencil</v-icon>
          </v-btn>
          <span>Item Master Data</span>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon dark color="red" @click="close">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-card-title>
      <v-divider />
      <v-card-text>
        <v-container>
          <v-row no-gutters>
            <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-select
                v-model="form.item_group_id"
                :items="itemGroup"
                label="Type"
                item-text="name"
                item-value="id"
                outlined
                persistent-hint
                dense
                hide-details="auto"
              >
              </v-select>
            </v-col>

            <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-text-field
                v-model="form.name"
                label="Name"
                placeholder="Name"
                outlined
                dense
                hide-details="auto"
              ></v-text-field>
            </v-col>

            <!--              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">-->
            <!--                <v-text-field-->
            <!--                  v-model="form.code"-->
            <!--                  label="Code"-->
            <!--                  outlined-->
            <!--                  dense-->
            <!--                  hide-details="auto"-->
            <!--                ></v-text-field>-->
            <!--              </v-col>-->

            <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-select
                v-model="form.category"
                :items="itemCategory"
                label="Category"
                placeholder="Category"
                outlined
                persistent-hint
                dense
                hide-details="auto"
              >
              </v-select>
            </v-col>

            <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-select
                v-model="form.unit"
                :items="itemUnit"
                label="Unit"
                placeholder="Unit"
                outlined
                persistent-hint
                dense
                hide-details="auto"
              >
              </v-select>
            </v-col>

            <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-textarea
                v-model="form.description"
                label="Descriptions"
                outlined
                dense
                rows="2"
              ></v-textarea>
            </v-col>

            <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <span>Buy Price</span>
              <hr />
            </v-col>

            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <vuetify-money
                v-model="form.purchase_price"
                v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                v-bind:options="moneyOptions"
                label="Cost"
                outlined
                dense
                hide-details="auto"
              ></vuetify-money>
            </v-col>
            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-autocomplete
                v-model="form.buy_account_id"
                :items="itemAccounts"
                item-text="name"
                item-value="id"
                label="Expense Account"
                outlined
                dense
                hide-details="auto"
              ></v-autocomplete>
            </v-col>
            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-select
                v-model="form.buy_tax_id"
                :items="itemTax"
                item-text="name"
                item-value="id"
                label="Default Buy Tax"
                outlined
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <span>Sales Price</span>
              <hr />
            </v-col>
            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <vuetify-money
                v-model="form.sale_price"
                v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                v-bind:options="moneyOptions"
                label="Sales Price"
                outlined
                dense
                hide-details="auto"
              ></vuetify-money>
            </v-col>
            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-autocomplete
                v-model="form.sell_account_id"
                :items="itemAccounts"
                item-text="name"
                item-value="id"
                label="Income Account"
                outlined
                dense
                hide-details="auto"
              ></v-autocomplete>
            </v-col>
            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-select
                v-model="form.sell_tax_id"
                :items="itemTax"
                item-text="name"
                item-value="id"
                label="Default Sell Tax"
                outlined
                dense
                hide-details="auto"
              ></v-select>
            </v-col>

            <v-col cols="12" md="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <span>Track Stock for This Item</span>
              <hr />
            </v-col>
            <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <vuetify-money
                v-model="form.minimum_stock"
                v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                v-bind:options="moneyOptions"
                label="Minimum Stock Quantity"
                outlined
                dense
                hide-details="auto"
              ></vuetify-money>
            </v-col>
            <v-col cols="12" md="8" class="pr-1 pl-1 pb-1 pt-1 mt-1">
              <v-autocomplete
                v-model="form.inventory_account"
                :items="itemAccounts"
                item-text="name"
                item-value="id"
                label="Default Inventory Account"
                outlined
                dense
                hide-details="auto"
              ></v-autocomplete>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>

      <v-divider />
      <v-card-actions>
        <v-spacer />
        <v-btn
          color="green darken-1"
          dark
          small
          :loading="submitLoad"
          @click="save()"
        >
          {{ buttonTitle }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Dropzone from 'nuxt-dropzone'
import 'nuxt-dropzone/dropzone.css'

export default {
  name: 'quotation',

  components: { Dropzone },

  props: {
    formTitle: {
      type: String,
      default: '',
    },
    formData: {
      type: Object,
      default() {
        return {}
      },
    },
  },

  data() {
    return {
      logo: '',
      dialog: true,
      submitLoad: false,
      form: this.formData,
      selectedItem: 1,
      itemCategory: [],
      itemGroup: [],
      itemUnit: [],
      itemAccounts: [],
      itemTax: [],
      images: [],
      statusProcessing: 'insert',
      valueWhenIsEmpty: '0',
      temp_image: null,
      url: '/api/inventory/items',
      moneyOptions: {
        suffix: '',
        length: 11,
        precision: 2,
      },
      options: {
        url: '/api/document-files',
        timeout: 9000000000,
        addRemoveLinks: true,
        withCredentials: true,
        thumbnailWidth: 50,
        thumbnailHeight: 50,
        acceptedFiles: 'image/*',
        dictDefaultMessage:
          "<span class='mdi mdi-cloud-upload'></span> UPLOAD HERE",
        // headers: {
        //   'X-XSRF-TOKEN': this.$cookies.get('XSRF-TOKEN'),
        // },
      },
    }
  },

  computed: {
    buttonTitle() {
      return this.$route.query.document === 0 ? 'save' : 'Update'
    }
  },

  activated() {
    this.dialog = true
  },

  mounted() {
    this.getItemCategory()
    this.getItemUnit()
    this.getAccounts()
    this.getTaxes()
    this.getItemGroups()
  },

  methods: {
    newData(form) {
      this.$refs.dialogForm.openDialog()
      this.statusProcessing = 'insert'
      this.form = Object.assign({}, form)
      this.getFiles()
    },

    close() {
      this.$router.back()
      this.$nuxt.$emit('getDataFromApi')
    },

    editItem(item, url) {
      const dataForm = item
      dataForm.category = JSON.parse(dataForm.category)
      this.form = Object.assign({}, dataForm)
      this.statusProcessing = 'update'
      this.$refs.dialogForm.openDialog()
      this.getFiles()
    },

    getItemCategory() {
      this.$axios
        .get(`/api/master/categories`, {
          params: {
            type: 'Item Category',
          },
        })
        .then((res) => {
          this.itemCategory = res.data.data.simple
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    getTaxes() {
      this.$axios
        .get(`/api/financial/taxes`, {
          params: {
            type: 'Item Category',
          },
        })
        .then((res) => {
          this.itemTax = res.data.data.row_simple
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    getItemGroups() {
      this.$axios
        .get(`/api/inventory/item-groups`, {
          params: {
            type: 'Item Category',
          },
        })
        .then((res) => {
          this.itemGroup = res.data.data.rows
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    getItemUnit() {
      this.$axios
        .get(`/api/inventory/item-units`, {
          params: {
            type: 'Item Category',
          },
        })
        .then((res) => {
          this.itemUnit = res.data.data.simple
        })
        .catch((err) => {
          this.$swal({
            type: 'error',
            title: 'Error',
            text: err.response.data.message,
          })
        })
    },

    getAccounts() {
      this.$axios
        .get(`/api/financial/accounts`, {
          params: {
            type: 'All',
          },
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

    sendingParams(file, xhr, formData) {
      const temp_id = this.form.id ? this.form.id : this.form.temp_id
      formData.append('temp_id', temp_id)
      formData.append('type', 'item')
    },

    handleError(file, message, xhr) {
      this.$swal({
        type: 'error',
        title: 'Error...',
        text: message.message,
      })
    },

    reloadAttachment(file, response) {
      if (response.errors) {
        this.$swal({
          type: 'error',
          title: 'Oops...',
          text: response.message,
        })
      } else {
        this.$emit('eventCountAttachment', {
          total: response.data.count,
          row: this.row,
        })

        setTimeout(() => {
          this.getFiles()
        }, 300)

        this.$swal({
          type: 'success',
          title: 'Success...',
          text: 'Attachment uploaded!',
        })
      }
    },

    getFiles() {
      this.showLoadingAttachment = true
      const vm = this
      const temp_id = this.form.id ? this.form.id : this.form.temp_id

      this.$axios
        .get(this.options.url, {
          params: {
            type: 'item',
            temp_id,
          },
        })
        .then((res) => {
          vm.images = res.data.data.rows
          vm.total = res.data.data.total
          vm.showLoadingAttachment = false
        })
        .catch((err) => {
          this.showLoadingAttachment = false
          this.$swal({
            type: 'error',
            title: 'Oops...',
            text: err.response.message,
          })
        })
    },

    returnData(data) {
      if (data.type === 'Item Category') {
        this.itemCategory = data.item
      } else if (data.type === 'Item Unit') {
        this.itemUnit = data.item
      }
    },

    save() {
      const vm = this
      const status = this.statusProcessing

      if (status === 'insert') {
        this.store('post', this.url, this.form)
        vm.submitLoad = false
      } else if (status === 'update') {
        this.store('put', this.url + '/' + this.form.id, this.form)
        vm.submitLoad = false
      }
    },

    store(method, url, data) {
      const vm = this
      vm.submitLoad = true
      this.$axios({ method, url, data })
        .then((res) => {
          this.$refs.dialogForm.closeDialog()
          this.$emit('getDataFromApi')
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
