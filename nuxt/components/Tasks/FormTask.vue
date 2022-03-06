<template>
  <div>
    <DialogForm
      ref="dialogForm"
      max-width="800px"
      :dialog-title="formTitle"
      button-title="Save"
    >
      <template #content>
        <v-form class="pt-2">
          <v-layout wrap>
            <v-flex md12 class="d-flex">
              <v-layout wrap>
                <v-flex md12 class="pa-1 mb-1">
                  <v-select
                    v-model="form.product_category"
                    :items="$auth.$storage.getLocalStorage('productCategory')"
                    label="Product Category"
                    placeholder="Product Category"
                    outlined
                    dense
                    hide-details="auto"
                  >
                    <template #append-outer>
                      <v-btn small icon>
                        <v-icon
                          small
                          color="orange"
                          @click="
                            $refs.formMaster.openForm(
                              '/api/products/category',
                              'Product Category'
                            )
                          "
                        >
                          mdi-arrow-right-bold
                        </v-icon>
                      </v-btn>
                    </template>
                  </v-select>
                </v-flex>

                <v-flex md12 class="pa-1 mb-1">
                  <v-select
                    v-model="form.product_brand"
                    :items="$auth.$storage.getLocalStorage('productCategory')"
                    label="Product Brand"
                    placeholder="Product Brand"
                    outlined
                    dense
                    hide-details="auto"
                  >
                    <template #append-outer>
                      <v-btn small icon>
                        <v-icon
                          small
                          color="orange"
                          @click="
                            $refs.formMaster.openForm(
                              '/api/products/brand',
                              'Product Brand'
                            )
                          "
                        >
                          mdi-arrow-right-bold
                        </v-icon>
                      </v-btn>
                    </template>
                  </v-select>
                </v-flex>

                <v-flex md12 class="pa-1 mb-1">
                  <v-text-field
                    v-model="form.product_name"
                    label="Product Name"
                    placeholder="Product Name"
                    outlined
                    dense
                    hide-details="auto"
                  ></v-text-field>
                </v-flex>

                <v-flex md12 class="pa-1 mb-1">
                  <span>Product Description</span>
                  <client-only>
                    <!-- Use the component in the right place of the template -->
                    <tiptap-vuetify
                      v-model="form.product_desc"
                      :extensions="extensions"
                    />

                    <template #placeholder> Loading... </template>
                  </client-only>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-form>
      </template>
      <template #saveData>
        <v-btn
          color="blue darken-1"
          dark
          small
          :loading="submitLoad"
          @click="save()"
        >
          {{ buttonTitle }}
        </v-btn>
      </template>
    </DialogForm>

    <LazyProductsFormMaster ref="formMaster" />
  </div>
</template>

<script>
import {
  TiptapVuetify,
  Heading,
  Bold,
  Italic,
  Strike,
  Underline,
  Code,
  Paragraph,
  BulletList,
  OrderedList,
  ListItem,
  Link,
  Blockquote,
  HardBreak,
  HorizontalRule,
  History,
  Image,
} from 'tiptap-vuetify'

export default {
  name: 'FormTask',

  components: { TiptapVuetify },

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
      dialog: false,
      submitLoad: false,
      form: this.formData,
      statusProcessing: 'insert',
      extensions: [
        History,
        Blockquote,
        Link,
        Underline,
        Strike,
        Italic,
        ListItem,
        BulletList,
        OrderedList,
        Image,
        [
          Heading,
          {
            options: {
              levels: [1, 2, 3],
            },
          },
        ],
        Bold,
        Link,
        Code,
        HorizontalRule,
        Paragraph,
        HardBreak,
      ],
    }
  },

  methods: {
    newData() {
      this.$refs.dialogForm.openDialog()
      this.statusProcessing = 'insert'
      this.form = Object.assign({}, this.defaultItem)
    },

    editItem(item) {
      this.form = Object.assign({}, item)
      this.statusProcessing = 'update'
      this.$refs.dialogForm.openDialog()
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
      const form = this.form
      const status = this.statusProcessing
      const data = {
        form,
        status,
      }

      if (status === 'insert') {
        this.store('post', '/api/products/product', data)
        vm.submitLoad = false
      } else if (status === 'update') {
        this.store('put', '/api/products/product/' + this.form.id, data)
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
