<template>
  <hot-table ref="details" :root="detailsRoot" :settings="settings"></hot-table>
</template>

<script>
import { HotTable } from '@handsontable/vue'
import { registerAllModules } from 'handsontable/registry'

import 'handsontable/dist/handsontable.full.css'

registerAllModules()

export default {
  name: 'PermissionList',
  components: {
    HotTable,
  },

  data() {
    return {
      settings: {
        licenseKey: 'non-commercial-and-evaluation',
      },
      detailsRoot: 'detailsRoot',
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

    updateTableSettings() {
      const vm = this
      this.$refs.details.hotInstance.updateSettings({
        colHeaders: ['Permission', 'Index', 'Store', 'Edits', 'Erase'],
        currentRowClassName: 'currentRow',
        currentColClassName: 'currentCol',
        startRows: 0,
        manualColumnFreeze: true,
        currData: {},
        rowHeaders: true,
        manualColumnResize: true,
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
        columns: [
          {
            type: 'autocomplete',
            data: 'permission',
            width: 150,
            strict: false,
            filter: true,
            height: 26,
            wordWrap: false,
            source(query, process) {
              const data = vm.$auth.$storage.getLocalStorage('permission_list')
              process(data)
            },
          },
          {
            data: 'index',
            width: 50,
            type: 'checkbox',
            checkedTemplate: 'Y',
            uncheckedTemplate: 'N',
          },
          {
            data: 'store',
            width: 50,
            type: 'checkbox',
            checkedTemplate: 'Y',
            uncheckedTemplate: 'N',
          },
          {
            data: 'edits',
            width: 50,
            type: 'checkbox',
            checkedTemplate: 'Y',
            uncheckedTemplate: 'N',
          },
          {
            data: 'erase',
            width: 50,
            type: 'checkbox',
            checkedTemplate: 'Y',
            uncheckedTemplate: 'N',
          },
        ],
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
