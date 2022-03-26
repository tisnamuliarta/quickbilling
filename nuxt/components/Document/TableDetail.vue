<template>
  <div>
    <hot-table ref="details" :root="detailsRoot" :settings="settings"></hot-table>

    <LazyInventoryDialogItem
      ref="dialogItem"
      :view-data="true"
      :show-add-btn="false"
      @selectItems="selectItems"
    ></LazyInventoryDialogItem>
  </div>
</template>

<script>
import {HotTable} from '@handsontable/vue'
// import the base only
import Handsontable from 'handsontable';
// choose cell types you want to use and import them
import { registerCellType, DropdownCellType, NumericCellType, CheckboxCellType } from 'handsontable/cellTypes';
// choose plugins you want to use and import them
import {
  registerPlugin,
  ManualColumnResize,
  AutoColumnSize,
  CopyPaste,
  Filters,
  PersistentState,
  HiddenColumns,
  ContextMenu,
  DropdownMenu,
  HiddenRows,
} from 'handsontable/plugins';

// register imported cell types and plugins
registerCellType(DropdownCellType);
registerCellType(NumericCellType);
registerCellType(CheckboxCellType);
registerPlugin(AutoColumnSize);
registerPlugin(ManualColumnResize);
registerPlugin(CopyPaste);
registerPlugin(Filters);
registerPlugin(PersistentState);
registerPlugin(HiddenColumns);
registerPlugin(HiddenRows);
registerPlugin(ContextMenu);
registerPlugin(DropdownMenu);

import 'handsontable/dist/handsontable.full.css'

export default {
  name: 'TableDetail',

  components: {
    HotTable,
  },

  data() {
    return {
      settings: {
        licenseKey: 'non-commercial-and-evaluation',
      },
      detailsRoot: 'detailsRoot',
      colHeaders: [],
      form: {},
    }
  },

  created() {
    this.setInstance()
  },

  methods: {
    setInstance() {
      window.details = this
    },

    removeRow(row) {
      this.$refs.details.hotInstance.alter('remove_row', row)
    },

    addLine() {
      const totalRow = this.$refs.details.hotInstance.countRows()
      this.$refs.details.hotInstance.alter('insert_row', totalRow + 1)
    },

    updateTableSettings(header) {
      this.$refs.details.hotInstance.updateSettings({
        currentRowClassName: 'currentRow',
        currentColClassName: 'currentCol',
        startRows: 2,
        rowHeaders: true,
        manualColumnResize: true,
        rowHeights: 28,
        filters: true,
        autoColumnSize: true,
        viewportRowRenderingOffset: 1000,
        viewportColumnRenderingOffset: 100,
        colWidths: 80,
        persistentState: true,
        width: '100%',
        stretchH: 'all',
        hiddenColumns: {
          copyPasteEnabled: false,
          indicator: false,
          columns: [1, 2, 3],
        },
        colHeaders: [
          '', 'Id', 'Item ID', 'Item Code', 'Item Name', 'Description', 'Qty', 'Units', 'Curency', 'Unit Price', 'Discount', 'Tax', 'Amount', '',
        ],
        columns: [
          // TODO
          {
            width: '24px',
            wordWrap: false,
            renderer(instance, td, row, col, prop, value, cellProperties) {
              let button = null
              const vm = window.details
              button = document.createElement('button')
              button.type = 'button'
              button.innerText = ">";
              button.className = "btnNPB";
              button.value = 'Details'

              Handsontable.dom.addEvent(button, 'mousedown', (event) => {
                event.preventDefault()
                vm.$refs.dialogItem.openDialog(row)
                // vm.$refs.inv.open()
              })

              Handsontable.dom.empty(td)
              td.appendChild(button)
              return td
            },
          },
          {
            data: 'id',
            width: 100,
            wordWrap: false,
          },
          {
            data: 'item_id',
            width: '50px',
            wordWrap: false,
          },
          {
            data: 'sku',
            width: '100px',
            readOnly: true,
            wordWrap: false,
          },
          {
            data: 'name',
            width: '150px',
            readOnly: true,
            wordWrap: false,
          },
          {
            data: 'description',
            width: '230px',
            wordWrap: false,
          },
          {
            data: 'quantity',
            width: '100px',
            wordWrap: false,
            type: 'numeric',
            numericFormat: {
              pattern: '0,0',
            },
          },
          {
            data: 'unit',
            width: '100px',
            readOnly: true,
            wordWrap: false,
          },
          {
            data: 'default_currency_symbol',
            width: '50px',
            readOnly: true,
            wordWrap: false,
            align: 'right',
          },
          {
            data: 'price',
            width: '100px',
            wordWrap: false,
            type: 'numeric',
            numericFormat: {
              pattern: '0,0.00',
            },
          },
          {
            data: 'discount_rate',
            width: '100px',
            wordWrap: false,
            type: 'numeric',
            numericFormat: {
              pattern: '0,0.00',
            },
          },
          {
            data: 'tax_name',
            width: '100px',
            type: 'dropdown',
            height: 26,
            wordWrap: false,
            source(query, process) {
              const vm = window.details
              const data = vm.$auth.$storage.getLocalStorage('tax')
              process(data)
            },
            strict: true,
            filter: false,
            allowInvalid: false,
          },
          {
            data: 'total',
            width: '100px',
            wordWrap: false,
            type: 'numeric',
            readOnly: true,
            numericFormat: {
              pattern: '0,0.00',
            },
          },
          {
            width: '24px',
            wordWrap: false,
            renderer(instance, td, row, col, prop, value, cellProperties) {
              let button = null
              const vm = window.details
              button = document.createElement('button')
              button.type = 'button'
              button.innerText = "X";
              button.className = "btnDelete";
              button.value = 'Details'

              Handsontable.dom.addEvent(button, 'mousedown', (event) => {
                event.preventDefault()
                vm.removeRow(row)
              })

              Handsontable.dom.empty(td)
              td.appendChild(button)
              return td
            }
          },
        ],
        contextMenu: {
          callback(key, options) {
            // eslint-disable-next-line no-unused-vars
            let indexArr, selectedData
            const vm = window.details
            // console.log(key)
            if (key === 'remove_row') {
              vm.isInvDetailChanges = true
              vm.isInvChanges = true
            }
          },
        },
        afterRemoveRow: (index, amount, physicalRow, source) => {
          const vm = window.details
          vm.calculateTotal()
        },
        beforeRemoveRow: (index, amount, physicalRow, source) => {
          const vm = window.details
          const id = []
          physicalRow.forEach(function (index, value) {
            const entry = vm.$refs.details.hotInstance.getDataAtCell(index, 0)
            if (entry) {
              id.push(entry)
            }
          })
          const countRows = vm.$refs.details.hotInstance.countRows()
          if (countRows === 1) {
            vm.$emit('calcTotal', {
              subTotal: 0,
              amount: 0,
              discountAmount: 0,
              taxDetail: []
            })
          }

          if (id.length > 0) {
            vm.$emit('removeData', {
              id,
            })
          }
          return true
        },

        afterChange: (changes, source) => {
          const vm = window.details
          if (changes) {
            let propNew = 0
            changes.forEach(([row, prop, oldValue, newValue]) => {
              propNew = prop
              if (propNew === 'quantity' || propNew === 'price' || propNew === 'discount_rate' || propNew === 'tax_name') {
                if (oldValue !== newValue) {
                  vm.calculateTotal()
                }
              }
            })
          }
        },
      })
    },

    selectItems(data) {
      let rowData = data.row;
      let selected = data.selected;
      const type = this.$route.query.type
      const vm = this;
      selected.forEach(function (item, index) {
        const price = (type.substr(0, 1) === 'S') ? item.sale_price : item.purchase_price
        const tax_name = (type.substr(0, 1) === 'S') ? item.sell_tax_name : item.buy_tax_name

        vm.$refs.details.hotInstance.setDataAtRowProp([
          [rowData, 'name', item.name],
          [rowData, 'sku', item.code],
          [rowData, 'unit', item.unit],
          [rowData, 'description', item.description],
          [rowData, 'default_currency_symbol', vm.form.default_currency_symbol],
          [rowData, 'item_id', item.id],
          [rowData, 'price', price],
          [rowData, 'tax_name', tax_name],
          [rowData, 'quantity', 1],
        ]);
        rowData++
      })
    },

    setDataToDetails(data, form) {
      this.updateTableSettings()
      this.form = form
      const vm = this
      const items = (form.items !== undefined) ? form.items : data
      vm.$refs.details.hotInstance.loadData(items)
      const countRows = this.$refs.details.hotInstance.countRows()
      for (let i = 0; i < countRows; i++) {
        this.$refs.details.hotInstance.setDataAtRowProp(i, 'default_currency_symbol', vm.form.default_currency_symbol)
      }
      // setTimeout(() => {
      //   vm.$refs.details.hotInstance.loadData(data)
      // }, 10)
    },

    calculateTotal() {
      const countRows = this.$refs.details.hotInstance.countRows()
      let subTotal = 0;
      let discountAmount = 0;
      let taxDetail = [];
      let amount = 0;
      let amountRow = 0;
      if (countRows > 0) {
        for (let i = 0; i < countRows; i++) {
          const qty = this.$refs.details.hotInstance.getDataAtRowProp(i, 'quantity')
          const unitPrice = this.$refs.details.hotInstance.getDataAtRowProp(i, 'price')
          const discount = this.$refs.details.hotInstance.getDataAtRowProp(i, 'discount_rate')
          const tax = this.$refs.details.hotInstance.getDataAtRowProp(i, 'tax_name')

          const subTotalRow = (qty * unitPrice)
          subTotal = subTotal + (qty * unitPrice)

          const discountPerLine = parseFloat((discount / 100) * subTotalRow).toFixed(2)
          discountAmount = parseFloat(discountAmount) + parseFloat(discountPerLine)

          amountRow = (subTotalRow - discountPerLine )

          if (tax) {
            let taxRate = 0
            this.$auth.$storage.getLocalStorage('tax_row').forEach(function (item, index) {
              if (item.name === tax) {
                taxRate = parseFloat(item.rate)
              }
            })

            const taxRow = (parseFloat(taxRate) / 100) * amountRow
            taxDetail.push({
              name: tax,
              amount: taxRow
            })
          }

          amount = amount +  (subTotalRow - discountPerLine )

          this.$refs.details.hotInstance.setDataAtRowProp(
            i,
            'total',
            amountRow
          )
        }
      }
      this.$emit('calcTotal', {
        subTotal,
        amount,
        discountAmount,
        taxDetail
      })
    },

    async getTaxRate(tax) {
      const taxRate = await this.$axios.get(`/api/financial/taxes/0`, {
        params: {
          name: tax
        }
      })
      return taxRate.data.data.rows
    },

    getDataAtRowPro(row, prop) {
      return this.$refs.details.hotInstance.getDataAtRowProp(row, prop)
    },

    countRows() {
      return this.$refs.details.hotInstance.countRows()
    },

    checkIfEmptyRow(key) {
      return this.$refs.details.hotInstance.isEmptyRow(key)
    },

    getAddData() {
      return this.$refs.details.hotInstance.getSourceData()
      // return this.$refs.details.hotInstance.getData()
    },
  },
}
</script>
