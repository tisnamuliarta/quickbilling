<template>
  <ag-grid-vue
    style="width: 100%; height: 100%;"
    class="ag-theme-alpine"
    :gridOptions="gridOptions"
    :columnDefs="columnDefs"
    :rowData="rowData"
    :defaultColDef="defaultColDef"
    :rowDragManaged="true"
    domLayout='autoHeight'
    :rowSelection="rowSelection"
    :suppressRowClickSelection="true"
    singleClickEdit="true"
    @grid-ready="onReady"
    @model-updated="onModelUpdated"
    @cell-clicked="onCellClicked"
    @cell-double-clicked="onCellDoubleClicked"
    @cell-context-menu="onCellContextMenu"
    @cell-value-changed="onCellValueChanged"
    @cell-focused="onCellFocused"
    @row-selected="onRowSelected"
    @selection-changed="onSelectionChanged"
    @filter-modified="onFilterModified"
    @virtual-row-removed="onVirtualRowRemoved"
    @row-clicked="onRowClicked"
    @column-everything-changed="onColumnEvent"
    @column-row-group-changed="onColumnEvent"
    @column-value-Changed="onColumnEvent"
    @column-moved="onColumnEvent"
    @column-visible="onColumnEvent"
    @column-group-Opened="onColumnEvent"
    @column-resized="onColumnEvent"
    @column-pinned-count-changed="onColumnEvent"
  >
  </ag-grid-vue>
</template>

<script>
import "ag-grid-community/dist/styles/ag-grid.css";
import "ag-grid-community/dist/styles/ag-theme-alpine.css";
import {AgGridVue} from "ag-grid-vue";
import ButtonDelete from "./Renderer/ButtonDelete";
import AutoComplete from '../Form/Renderer/AutoComp.js'

window.currencyFormatter = function currencyFormatter(params) {
  return formatNumber(params.value);
};

window.formatNumber = function formatNumber(number) {
  // this puts commas into the number eg 1000 goes to 1,000,
  // i pulled this from stack overflow, i have no idea how it works
  return Math.floor(number)
    .toString()
    .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
};

export default {
  name: "AgGrid",

  components: {
    AgGridVue,
    buttonDelete: ButtonDelete,
    autoComplete: AutoComplete
  },

  data() {
    return {
      gridOptions: null,
      api: null,
      columnDefs: [
        {
          headerName: '#',
          valueGetter: 'node.rowIndex + 1',
          rowDrag: false,
          headerCheckboxSelection: true,
          headerCheckboxSelectionFilteredOnly: true,
          checkboxSelection: true,
          editable: false,
          width: 10,
        },
        {
          field: "product",
          headerName: 'Product/Service',
          editable: true,
          cellEditor: "autoComplete",
          cellEditorParams: {
            propertyRendered: "city",
            returnObject: true,
            rowData: [
              { id: 1, city: "Paris", country: "France" },
              { id: 2, city: "London", country: "United Kingdom" },
              { id: 3, city: "Berlin", country: "Germany" },
              { id: 4, city: "Madrid", country: "Spain" },
              { id: 5, city: "Rome", country: "Italy" },
              { id: 6, city: "Copenhagen", country: "Denmark" },
              { id: 7, city: "Brussels", country: "Belgium" },
              { id: 8, city: "Amsterdam", country: "The Netherlands" },
            ],
            columnDefs: [
              { headerName: "City", field: "city" },
              { headerName: "Country", field: "country" },
            ],
          },
          valueFormatter: (params) => {
            if (params.value) return params.value.city;
            return "";
          },
        },
        {field: "description"},
        {field: "qty", width: 50, type: 'rightAligned', valueFormatter: currencyFormatter},
        {field: "rate", width: 50, type: 'rightAligned', valueFormatter: currencyFormatter},
        {field: "amount", width: 50, type: 'rightAligned', valueFormatter: currencyFormatter},
        {field: "tax", width: 50, type: 'rightAligned', valueFormatter: currencyFormatter},
        {
          headerName: '',
          field: 'delete',
          cellRenderer: 'buttonDelete',
          colId: 'params',
          editable: false,
          width: 5,
        },
      ],
      rowData: [
        {product: "Toyota1", description: "Celica", qty: 35000.899, rate: 300, amount: 200, tax: 0},
        {product: "Toyota2", description: "Celica", qty: 35000, rate: 300, amount: 200, tax: 0},
        {product: "Toyota3", description: "Celica", qty: 35000, rate: 300, amount: 200, tax: 0},
      ],
      showGrid: false,
      sideBar: false,
      rowCount: null,
      rowSelection: 'multiple',
      defaultColDef: {
        // set the default column width
        width: 150,
        // make every column editable
        editable: true,
        // make columns resizable
        resizable: true,
      },
    }
  },

  beforeMount() {
    this.gridOptions = {};
  },

  methods: {
    addItems(addIndex) {
      const newItems = [
        {product: "Toyota1", description: "Celica", qty: 35000, rate: 300, amount: 200, tax: 0},
      ];
      this.api.applyTransaction({
        add: newItems,
        addIndex: addIndex,
      });
    },

    calculateRowCount() {
      if (this.api && this.rowData) {
        let model = this.gridOptions.api.getModel();
        let totalRows = this.rowData.length;
        let processedRows = model.getRowCount();
        this.rowCount = processedRows.toLocaleString() + ' / ' + totalRows.toLocaleString();
      }
    },

    onModelUpdated() {
      console.log('onModelUpdated');
      this.calculateRowCount();
    },

    onReady(params) {
      this.api = params.api;
      this.calculateRowCount();

      this.api.sizeColumnsToFit();
    },

    onCellClicked(event) {
      console.log('onCellClicked: ' + event.rowIndex + ' ' + event.colDef.field);
    },

    onCellValueChanged(event) {
      console.log('onCellValueChanged: ' + event.oldValue + ' to ' + event.newValue);
    },

    onCellDoubleClicked(event) {
      console.log('onCellDoubleClicked: ' + event.rowIndex + ' ' + event.colDef.field);
    },

    onCellContextMenu(event) {
      console.log('onCellContextMenu: ' + event.rowIndex + ' ' + event.colDef.field);
    },

    onCellFocused(event) {
      console.log('onCellFocused: (' + event.rowIndex + ',' + event.colIndex + ')');
    },

    // taking out, as when we 'select all', it prints to much to the console!!
    // eslint-disable-next-line
    onRowSelected(event) {
      // console.log('onRowSelected: ' + event.node.data.name);
    },

    onSelectionChanged() {
      console.log('selectionChanged');
    },

    onFilterModified() {
      console.log('onFilterModified');
    },

    // eslint-disable-next-line
    onVirtualRowRemoved(event) {
      // because this event gets fired LOTS of times, we don't print it to the
      // console. if you want to see it, just uncomment out this line
      // console.log('onVirtualRowRemoved: ' + event.rowIndex);
    },

    onRowClicked(event) {
      console.log('onRowClicked: ' + event.node.data.name);
    },

    onQuickFilterChanged(event) {
      this.gridOptions.api.setQuickFilter(event.target.value);
    },

    // here we use one generic event to handle all the column type events.
    // the method just prints the event name
    onColumnEvent(event) {
      console.log('onColumnEvent: ' + event);
    }
  }
}
</script>
