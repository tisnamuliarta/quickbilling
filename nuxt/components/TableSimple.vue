<template>
  <hot-table ref="details" :root="detailsRoot" :settings="settings"></hot-table>
</template>

<script>
import { HotTable } from '@handsontable/vue'
// choose cell types you want to use and import them
import { registerCellType, DropdownCellType } from 'handsontable/cellTypes';
// choose plugins you want to use and import them
import {
  registerPlugin,
  AutoColumnSize,
  CopyPaste,
  Filters,
  HiddenColumns,
  ContextMenu,
} from 'handsontable/plugins';

// register imported cell types and plugins
registerCellType(DropdownCellType);
registerPlugin(AutoColumnSize);
registerPlugin(CopyPaste);
registerPlugin(Filters);
registerPlugin(HiddenColumns);
registerPlugin(ContextMenu);

import 'handsontable/dist/handsontable.full.css'

export default {
  name: 'TableSimple',

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

    addLine() {
      const totalRow = this.$refs.details.hotInstance.countRows()
      this.$refs.details.hotInstance.alter('insert_row', totalRow + 1)
    },

    updateTableSettings(header) {
      this.$refs.details.hotInstance.updateSettings({
        colHeaders: header,
        currentRowClassName: 'currentRow',
        currentColClassName: 'currentCol',
        startRows: 1,
        currData: {},
        rowHeaders: true,
        filters: true,
        autoColumnSize: false,
        viewportRowRenderingOffset: 1000,
        viewportColumnRenderingOffset: 100,
        colWidths: 80,
        width: '100%',
        stretchH: 'all',
        hiddenColumns: {
          copyPasteEnabled: false,
          indicator: false,
          columns: [0],
        },
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

    setDataToDetails(data, colHeaders) {
      this.updateTableSettings(colHeaders)
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
