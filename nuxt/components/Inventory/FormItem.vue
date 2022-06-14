<template>
  <div>
    <DialogForm
      ref="dialogForm"
      max-width="600px"
      :dialog-title="formTitle"
      button-title="Save"
    >
      <template #content>
        <v-form>
          <v-container>
            <v-row dense>
              <v-col cols="12" md="6">
                <v-autocomplete
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
                  <template #item="data">
                    <v-list-item-content>
                      <v-list-item-title
                        class="font-weight-bold"
                        v-text="data.item.name"
                      ></v-list-item-title>
                      <v-list-item-subtitle
                        v-text="data.item.desc"
                      ></v-list-item-subtitle>
                    </v-list-item-content>
                  </template>
                </v-autocomplete>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="form.name"
                  label="Name"
                  placeholder="Name"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="form.code"
                  label="Code"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col cols="12" md="6">
                <v-autocomplete
                  v-model="form.category"
                  :items="itemCategory"
                  label="Category"
                  placeholder="Category"
                  outlined
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                  <template #prepend-item>
                    <div>
                      <v-btn text block small class="text-left">
                        <v-icon>mdi-plus</v-icon>
                        Add New
                      </v-btn>
                    </div>
                  </template>
                </v-autocomplete>
              </v-col>

              <v-col cols="12" md="6">
                <v-autocomplete
                  v-model="form.unit"
                  :items="itemUnit"
                  label="Unit"
                  placeholder="Unit"
                  outlined
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                </v-autocomplete>
              </v-col>

              <v-col cols="12" md="6">
                <v-autocomplete
                  v-model="form.classification_id"
                  :items="itemClassification"
                  label="Unit"
                  item-text="name"
                  item-value="id"
                  outlined
                  persistent-hint
                  dense
                  hide-details="auto"
                >
                </v-autocomplete>
              </v-col>

              <v-col v-if="form.item_group_id === 1" cols="12" md="4">
                <vuetify-money
                  v-model="form.onhand"
                  v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                  v-bind:options="moneyOptions"
                  label="Initial quantity onhand"
                  class="text-money"
                  outlined
                  dense
                  hide-details="auto"
                ></vuetify-money>
              </v-col>

              <v-col v-if="form.item_group_id === 1" cols="12" md="4">
                <v-menu
                  ref="menu"
                  v-model="menu"
                  :close-on-content-click="false"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template #activator="{ on, attrs }">
                    <v-text-field
                      v-model="form.onhand_date"
                      label="As of date"
                      prepend-icon="mdi-calendar"
                      readonly
                      persistent-hint
                      outlined
                      dense
                      hide-details="auto"
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>

                  <v-date-picker
                    v-model="form.onhand_date"
                    no-title
                    @input="menu = false"
                  >
                  </v-date-picker>
                </v-menu>
              </v-col>

              <v-col v-if="form.item_group_id === 1" cols="12" md="4">
                <vuetify-money
                  v-model="form.reorder_point"
                  v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                  v-bind:options="moneyOptions"
                  label="Reorder point"
                  class="text-money"
                  outlined
                  dense
                  hide-details="auto"
                ></vuetify-money>
              </v-col>

              <v-col cols="12" md="12">
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

              <v-col cols="12" md="4">
                <vuetify-money
                  v-model="form.commision_rate"
                  v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                  v-bind:options="moneyOptions"
                  label="Commission Rate"
                  class="text-money"
                  outlined
                  dense
                  hide-details="auto"
                ></vuetify-money>
              </v-col>

              <v-col cols="12" md="12" class="pb-0 font-weight-bold">
                <span>Sales Information</span>
              </v-col>

              <v-col cols="12" md="10">
                <v-checkbox
                  v-model="form.is_sell"
                  hide-details="auto"
                  label="I sell this product/service to customers"
                ></v-checkbox>
              </v-col>

              <v-col v-if="form.is_sell" cols="12">
                <v-text-field
                  v-model="form.description"
                  label="Descriptions"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col v-if="form.is_sell" cols="12" md="3">
                <vuetify-money
                  v-model="form.sale_price"
                  v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                  v-bind:options="moneyOptions"
                  label="Sales Price"
                  class="text-money"
                  outlined
                  dense
                  hide-details="auto"
                ></vuetify-money>
              </v-col>

              <v-col v-if="form.is_sell" cols="12" md="9">
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

              <v-col v-if="form.is_sell" cols="12" md="12">
                <v-autocomplete
                  v-model="form.sell_tax_id"
                  :items="itemTax"
                  item-text="name"
                  item-value="id"
                  label="Sales Tax"
                  outlined
                  dense
                  hide-details="auto"
                ></v-autocomplete>
              </v-col>

              <v-col cols="12" md="12" class="pb-0 pt-0 font-weight-bold">
                <span>Purchase Information</span>
              </v-col>

              <v-col cols="12" md="10">
                <v-checkbox
                  v-model="form.is_purchase"
                  hide-details="auto"
                  label="I purchase this product/service from vendor"
                ></v-checkbox>
              </v-col>

              <v-col v-if="form.is_purchase" cols="12">
                <v-text-field
                  v-model="form.purchase_description"
                  label="Descriptions"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

              <v-col v-if="form.is_purchase" cols="12" md="3">
                <vuetify-money
                  v-model="form.purchase_price"
                  v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                  v-bind:options="moneyOptions"
                  class="text-money"
                  label="Cost"
                  outlined
                  dense
                  hide-details="auto"
                ></vuetify-money>
              </v-col>

              <v-col v-if="form.is_purchase" cols="12" md="9">
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

              <v-col v-if="form.is_purchase" cols="12" md="12">
                <v-autocomplete
                  v-model="form.contact_id"
                  :items="itemContact"
                  item-text="name"
                  item-value="id"
                  label="Prefered Vendor"
                  outlined
                  dense
                  hide-details="auto"
                ></v-autocomplete>
              </v-col>
            </v-row>
          </v-container>
        </v-form>
      </template>
      <template #saveData>
        <v-btn
          color="green darken-1"
          dark
          small
          :loading="submitLoad"
          @click="save()"
        >
          {{ buttonTitle }}
        </v-btn>
      </template>
    </DialogForm>

    <LazyInventoryFormMaster
      ref="formMaster"
      @returnData="returnData"
    ></LazyInventoryFormMaster>
  </div>
</template>

<script>
import Dropzone from 'nuxt-dropzone'
import 'nuxt-dropzone/dropzone.css'

export default {
  name: 'FormProduct',

  components: { Dropzone },

  props: {
    formTitle: {
      type: String,
      default: '',
    },
    buttonTitle: {
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
      dialog: false,
      submitLoad: false,
      form: this.formData,
      selectedItem: 1,
      itemCategory: [],
      itemUnit: [],
      itemClassification: [],
      itemAccounts: [],
      itemGroup: [
        {
          id: 1,
          name: 'Inventory',
          desc: 'Product you buy and/or sell and you track quantities of',
        },
        {
          id: 2,
          name: 'Non inventory',
          desc: "Product you buy and/or sell but you don't need to (or can't) track quantities of, for example nuts and bolts used in an installation",
        },
        {
          id: 3,
          name: 'Service',
          desc: 'Service that you provide to customers, for example, landscaping or tax preparation services',
        },
        {
          id: 4,
          name: 'Bundle',
          desc: 'A collection of products and/or services that you sell together, for example a gift basket of fruit, cheese, and whine',
        },
      ],
      menu: '',
      itemTax: [],
      itemContact: [],
      images: [],
      statusProcessing: 'insert',
      valueWhenIsEmpty: null,
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

  mounted() {
    this.getItemCategory()
    this.getItemUnit()
    this.getAccounts()
    this.getTaxes()
    this.getContacts()
  },

  methods: {
    newData(form) {
      this.$refs.dialogForm.openDialog()
      this.statusProcessing = 'insert'
      this.form = Object.assign({}, form)
      this.getFiles()
    },

    editItem(item, url) {
      const dataForm = item
      // dataForm.category = JSON.parse(dataForm.category)
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

    getContacts() {
      this.$axios
        .get(`/api/bp/contacts`, {
          params: {
            type: 'Item Category',
          },
        })
        .then((res) => {
          this.itemContact = res.data.data.rows
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

    close() {
      this.$refs.dialogForm.closeDialog()
      this.statusProcessing = 'insert'
      setTimeout(() => {
        this.form = Object.assign({}, this.defaultItem)
      }, 300)
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
