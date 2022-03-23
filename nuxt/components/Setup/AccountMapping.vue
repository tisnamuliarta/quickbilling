<template>
  <v-row no-gutters>
    <v-col cols="12" class="pr-md-3 pl-md-3">
      <v-row v-for="(item, index) in accountMapping" :key="index" no-gutters>
        <v-col cols="12" class="mb-2">
          <v-subheader>{{ item.type }}</v-subheader>
          <v-divider></v-divider>
        </v-col>
        <v-col v-for="child in item.items" :key="child.id"  cols="12" md="6" class="pr-1 pl-1 pb-1 pt-1 mt-1">
          <v-autocomplete
            :items="itemAccounts"
            :label="child.label"
            v-model="form[child.name]"
            item-value="id"
            item-text="name"
            outlined
            clearable
            dense
            hide-details="auto"
          ></v-autocomplete>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
export default {
  name: 'EmailSetup',

  props: {
    formData: {
      type: Object,
      default() {
        return {}
      },
    }
  },

  data() {
    return {
      itemAccounts: [],
      accountMapping: [],
      form: this.formData,
      paperSize: [
        'A4 Portrait',
        'A4 Landscape',
        'Letter Portrait',
        'Letter Landscape',
      ],
    }
  },

  created() {
    this.getAccounts()
    this.getAccountMapping()
  },

  methods: {
    getAccountMapping() {
      const vm  = this
      this.$axios.get(`/api/financial/account-mapping`)
        .then(res => {
          this.accountMapping = res.data.data
          Object.entries(vm.accountMapping).forEach(entry => {
            const [key, value] = entry;
            Object.entries(value.items).forEach(entries => {
              const [k, val] = entries;
              vm.form[val.name] = val.account_id
            });
          });
        })
        // .catch((err) => {
        //   this.$swal({
        //     type: 'error',
        //     title: 'Error',
        //     text: err.response.data.message,
        //   })
        // })
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

    getForm() {
      return this.form
    },

    setForm(form) {
      this.form = Object.assign({}, form)
      this.getAccountMapping()
    }
  }
}
</script>
