<template>
  <div>
    <DialogForm
      ref="dialogForm"
      max-width="700px"
      :dialog-title="formTitle"
      button-title="Save"
    >
      <template #content>
        <v-form class="pt-2">
          <v-container>
            <v-row no-gutters>
              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-file-input
                  accept="image/*"
                  label="Image"
                  placeholder="Image"
                  v-model="form.image_temp"
                  outlined
                  dense
                  hide-details="auto"
                ></v-file-input>
              </v-col>

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-img
                  max-width="250"
                  max-height="150"
                  :src="logo"
                ></v-img>
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

              <v-col cols="12" class="pr-1 pl-1 pb-1 pt-1 mt-1">
                <v-text-field
                  v-model="form.code"
                  label="Code"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
              </v-col>

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
                <span>Descriptions</span>
                <client-only>
                  <!-- Use the component in the right place of the template -->
                  <tiptap-vuetify
                    v-model="form.description"
                    :extensions="extensions"
                  />

                  <template #placeholder> Loading...</template>
                </client-only>
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
                  v-model="form.buy_account"
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
                <v-text-field
                  v-model="form.buy_tax"
                  label="Default Buy Tax"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
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
                  v-model="form.sell_account"
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
                <v-text-field
                  v-model="form.sell_tax"
                  label="Default Sell Tax"
                  outlined
                  dense
                  hide-details="auto"
                ></v-text-field>
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
                  v-model="form.inventory_account_name"
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
import {
  Blockquote,
  Bold,
  BulletList,
  HardBreak,
  Heading,
  History,
  HorizontalRule,
  Italic,
  ListItem,
  OrderedList,
  Paragraph,
  Strike,
  TiptapVuetify,
  Underline,
} from 'tiptap-vuetify'

export default {
  name: 'FormProduct',

  components: {TiptapVuetify},

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
      itemCategory: [],
      itemUnit: [],
      itemAccounts: [],
      statusProcessing: 'insert',
      valueWhenIsEmpty: '0',
      url: '/api/inventory/items',
      moneyOptions: {
        suffix: "",
        length: 11,
        precision: 2
      },
      extensions: [
        History,
        Blockquote,
        Underline,
        Strike,
        Italic,
        ListItem,
        BulletList,
        OrderedList,
        [
          Heading,
          {
            options: {
              levels: [1, 2, 3],
            },
          },
        ],
        Bold,
        HorizontalRule,
        Paragraph,
        HardBreak,
      ],
    }
  },

  mounted() {
    this.getItemCategory()
    this.getItemUnit()
    this.getAccounts()
  },

  methods: {
    newData() {
      this.$refs.dialogForm.openDialog()
      this.statusProcessing = 'insert'
      this.form = Object.assign({}, this.defaultItem)
    },

    editItem(item, url) {
      this.form = Object.assign({}, item)
      this.logo = url + '/files/items/' + this.form.image
      this.statusProcessing = 'update'
      this.$refs.dialogForm.openDialog()
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

      let data = new FormData()
      Object.entries(this.form).forEach(entry => {
        const [key, value] = entry
        data.append(key, value)
      })

      if (status === 'insert') {
        this.store('post', this.url, data)
        vm.submitLoad = false
      } else if (status === 'update') {
        this.store('put', this.url + '/' + this.form.id, data)
        vm.submitLoad = false
      }
    },

    store(method, url, data) {
      const vm = this
      vm.submitLoad = true
      let options = {
        headers: {
          'Content-Type': "Multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
        }
      }
      if (method === 'post') {
        this.$axios.post(url, data, options)
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
      } else {
        this.$axios.put(url, data, options)
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
      }
    },
  },
}
</script>
