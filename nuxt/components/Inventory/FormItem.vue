<template>
  <div>
    <DialogForm
      ref="dialogForm"
      max-width="700px"
      :dialog-title="formTitle"
      button-title="Save"
    >
      <template #content>
        <v-form>
          <v-container>
            <v-row no-gutters>
              <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <span>Image</span>
                <dropzone
                  id='attachment'
                  ref='attachment'
                  :options='options'
                  :destroy-dropzone='true'
                  @vdropzone-sending="(file, xhr, formData) => sendingParams(file, xhr, formData)"
                  @vdropzone-success="(file, response) => reloadAttachment(file, response)"
                  @vdropzone-error="(file, message, xhr) => handleError(file, message, xhr)"
                ></dropzone>
              </v-col>

              <v-col cols="12" md="8" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-list dense>
                  <v-subheader>Images</v-subheader>
                  <v-list-item-group
                    v-model="selectedItem"
                    color="primary"
                  >
                    <v-list-item
                      v-for="(item, i) in images"
                      :key="i"
                    >
                      <v-list-item-content>
                        <v-list-item-title>
                          <a :href="item.directory" target="_blank">{{ item.filename }}</a>
                        </v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                  </v-list-item-group>
                </v-list>
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
                  multiple
                  persistent-hint
                  dense
                  hint="Exp. Component, Services, Design"
                  hide-details="auto"
                >
                  <template #prepend>
                    <v-btn small icon>
                      <v-icon
                        small
                        color="orange"
                        @click="
                            $refs.formMaster.openForm(
                              '/api/master/categories',
                              'Item Category',
                              'Item Category',
                              '400px'
                            )
                          "
                      >
                        mdi-arrow-right-bold
                      </v-icon>
                    </v-btn>
                  </template>
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
                  <template #prepend>
                    <v-btn small icon>
                      <v-icon
                        small
                        color="orange"
                        @click="
                            $refs.formMaster.openForm(
                              '/api/inventory/item-units',
                              'Item Unit',
                              'Item Unit',
                              '400px'
                            )
                          "
                      >
                        mdi-arrow-right-bold
                      </v-icon>
                    </v-btn>
                  </template>
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
                <hr>
              </v-col>

              <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <vuetify-money
                  v-model="form.purchase_price"
                  v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                  v-bind:options="moneyOptions"
                  label="Buy Unit Price"
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
                  label="Buy Account"
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
                <span>Sell Price</span>
                <hr>
              </v-col>
              <v-col cols="12" md="4" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <vuetify-money
                  v-model="form.sale_price"
                  v-bind:valueWhenIsEmpty="valueWhenIsEmpty"
                  v-bind:options="moneyOptions"
                  label="Buy Unit Price"
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
                  label="Sell Account"
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
                <hr>
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

    <LazyInventoryFormMaster ref="formMaster" @returnData="returnData"></LazyInventoryFormMaster>
  </div>
</template>

<script>
import Dropzone from 'nuxt-dropzone'
import 'nuxt-dropzone/dropzone.css'

export default {
  name: 'FormProduct',

  components: {Dropzone},

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
      itemAccounts: [],
      itemTax: [],
      images: [],
      statusProcessing: 'insert',
      valueWhenIsEmpty: '0',
      temp_image: null,
      url: '/api/inventory/items',
      moneyOptions: {
        suffix: "",
        length: 11,
        precision: 2
      },
      options: {
        url: '/api/document-files',
        timeout: 9000000000,
        addRemoveLinks: true,
        withCredentials: true,
        thumbnailWidth: 50,
        thumbnailHeight: 50,
        acceptedFiles: 'image/*',
        dictDefaultMessage: '<span class=\'mdi mdi-cloud-upload\'></span> UPLOAD HERE',
        headers: {
          'X-XSRF-TOKEN': this.$cookies.get('XSRF-TOKEN')
        }
      },
    }
  },

  mounted() {
    this.getItemCategory()
    this.getItemUnit()
    this.getAccounts()
    this.getTaxes()
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
      dataForm.category = JSON.parse(dataForm.category)
      this.form = Object.assign({}, dataForm)
      this.statusProcessing = 'update'
      this.$refs.dialogForm.openDialog()
      this.getFiles()
    },

    getItemCategory() {
      this.$axios.get(`/api/master/categories`, {
        params: {
          type: 'Item Category'
        }
      }).then((res) => {
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
      this.$axios.get(`/api/financial/taxes`, {
        params: {
          type: 'Item Category'
        }
      }).then((res) => {
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

    getItemUnit() {
      this.$axios.get(`/api/inventory/item-units`, {
        params: {
          type: 'Item Category'
        }
      }).then((res) => {
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

    sendingParams(file, xhr, formData) {
      const temp_id = (this.form.id) ? this.form.id : this.form.temp_id;
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
          row: this.row
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
      const temp_id = (this.form.id) ? this.form.id : this.form.temp_id;

      this.$axios.get(this.options.url, {
        params: {
          type: 'item',
          temp_id
        }
      })
        .then(res => {
          vm.images = res.data.data.rows
          vm.total = res.data.data.total
          vm.showLoadingAttachment = false
        })
        .catch(err => {
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
      this.$axios({method, url, data})
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
