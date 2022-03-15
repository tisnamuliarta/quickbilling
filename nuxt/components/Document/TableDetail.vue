<template>
  <hot-table ref="details" :root="detailsRoot" :settings="settings"></hot-table>
</template>

<script>
import {HotTable} from '@handsontable/vue'
import Handsontable from 'handsontable'
import {registerAllModules} from 'handsontable/registry'

import 'handsontable/dist/handsontable.full.css'

registerAllModules()

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
        manualColumnFreeze: true,
        currData: {},
        rowHeaders: true,
        manualColumnResize: true,
        rowHeights: 28,
        manualRowResize: true,
        filters: true,
        autoRowSize: false,
        autoColumnSize: false,
        viewportRowRenderingOffset: 1000,
        viewportColumnRenderingOffset: 100,
        colWidths: 80,
        dropdownMenu: true,
        columnSorting: true,
        persistentState: true,
        width: '100%',
        stretchH: 'all',
        hiddenColumns: {
          copyPasteEnabled: false,
          indicator: false,
          columns: [20],
        },
        colHeaders: [
          '', 'Product', 'Description', 'Qty', 'Units', 'Unit Price', 'Discount', 'Tax', 'Amount', ''
        ],
        columns: [
          // TODO
          {
            width: 10,
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
                vm.$emit('openDialogAsset', {
                  row
                })
                // vm.$refs.inv.open()
              })

              Handsontable.dom.empty(td)
              td.appendChild(button)
              return td
            },
          },
          {
            data: 'item_name',
            width: 100,
            wordWrap: false,
          },
          {
            data: 'description',
            width: 100,
            wordWrap: false,
          },
          {
            data: 'qty',
            width: 40,
            wordWrap: false,
            type: 'numeric',
            numericFormat: {
              pattern: '0,0',
            },
          },
          {
            data: 'unit',
            width: 30,
            wordWrap: false,
          },
          {
            data: 'unit_price',
            width: 50,
            wordWrap: false,
            type: 'numeric',
            numericFormat: {
              pattern: '0,0.00',
            },
          },
          {
            data: 'discount',
            width: 30,
            wordWrap: false,
          },
          {
            data: 'tax',
            width: 30,
            wordWrap: false,
          },
          {
            data: 'amount',
            width: 50,
            wordWrap: false,
            type: 'numeric',
            numericFormat: {
              pattern: '0,0.00',
            },
          },
          {
            width: 10,
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
        beforeRemoveRow: (index, amount, physicalRow, source) => {
          const vm = window.details
          const delQuestion = prompt('Are your sure want to delete this rows?')
          const id = []
          if (delQuestion === '') {
            physicalRow.forEach(function (index, value) {
              const entry = vm.$refs.details.hotInstance.getDataAtCell(index, 0)
              if (entry) {
                id.push(entry)
              }
            })
            if (id.length > 0) {
              vm.$emit('removeData', {
                id,
              })
            }
            return true
          } else {
            return false
          }
        },
      })
    },

    setDataToDetails(data) {
      this.updateTableSettings()
      const vm = this
      setTimeout(() => {
        vm.$refs.details.hotInstance.loadData(data)
      }, 300)
    },

    getAddData() {
      return this.$refs.details.hotInstance.getSourceData()
      // return this.$refs.details.hotInstance.getData()
    },
  },
}
</script>
