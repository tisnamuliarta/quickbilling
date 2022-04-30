<template>
  <v-dialog
    v-model="dialog"
    width="800"
    transition="dialog-top-transition"
  >
    <v-card>
      <v-container>
        <v-row>
          <v-col cols="12" md="3">
            <v-list dense>
              <span class="font-weight-bold subtitle-1 ml-4">SALES</span>
              <v-divider class="ml-4" />
              <v-list-item-group
                color="primary"
              >
                <v-list-item
                  v-for="(item, i) in itemSales"
                  :key="i"
                  @click="processAction(item)"
                >
                  <v-list-item-content>
                    <v-list-item-title v-text="item.text"></v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-col>

          <v-col cols="12" md="3">
            <v-list dense>
              <span class="font-weight-bold subtitle-1 ml-4">PURCHASING</span>
              <v-divider class="ml-4" />
              <v-list-item-group
                color="primary"
              >
                <v-list-item
                  v-for="(item, i) in itemPurchase"
                  :key="i"
                  @click="processAction(item)"
                >
                  <v-list-item-content>
                    <v-list-item-title v-text="item.text"></v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-col>

          <v-col cols="12" md="3">
            <v-list dense>
              <span class="font-weight-bold subtitle-1 ml-4">OTHER</span>
              <v-divider class="ml-4" />
              <v-list-item-group
                color="primary"
              >
                <v-list-item
                  v-for="(item, i) in itemOther"
                  :key="i"
                  @click="processAction(item)"
                >
                  <v-list-item-content>
                    <v-list-item-title v-text="item.text"></v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "New",

  data() {
    return {
      dialog: false,
      itemSales: [
        { text: 'Sales Quotations', type: 'SQ', action: 'document' },
        { text: 'Sales Order', type: 'SO', action: 'document' },
        { text: 'Sales Delivery', type: 'SD', action: 'document' },
        { text: 'A/R Invoice', type: 'SI', action: 'transaction' },
        { text: 'Incoming Payment', type: 'SP', action: 'transaction' },
        { text: 'Credit Memo', type: 'SP', action: 'transaction' },
        { text: 'Sales Return', type: 'SR', action: 'transaction' },
      ],

      itemPurchase: [
        { text: 'Expense', type: 'EXPENSE', action: 'document' },
        { text: 'Purchase Order', type: 'PO', action: 'document' },
        { text: 'A/P Invoice', type: 'PI', action: 'transaction' },
        { text: 'Outgoing Payment', type: 'PP', action: 'transaction' },
      ],

      itemOther: [
        { text: 'Bank Deposit', type: 'DEPOSIT', action: 'transaction' },
        { text: 'Transfer', type: 'TRANSFER', action: 'transaction' },
        { text: 'Journal Entry', type: 'JE', action: 'transaction' },
        { text: 'Statement', type: 'STATEMENT', action: 'transaction' },
        { text: 'Inventory Qty Adjustment', type: 'INVADJ', action: 'transaction' },
      ],
    }
  },

  methods: {
    openDialog() {
      this.dialog = true
    },

    processAction(item) {
      this.$emit('openAction', {
        item
      })
      this.dialog = false
    },
  }
}
</script>
