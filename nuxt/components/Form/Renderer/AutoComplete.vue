<template>
  <div ref="autoCompleteArea" :style="{ width: gridWidth + 'px' }">
    <input
      ref="input"
      v-model="inputValue"
      style=" height: 28px; font-weight: 400; font-size: 12px;"
      :style="{ width: params.column.actualWidth + 'px' }"
    />
    <ag-grid-vue
      style="font-weight: 150;"
      :style="{ height: gridHeight + 'px', 'max-width': gridWidth + 'px' }"
      class="ag-theme-alpine"
      :columnDefs="columnDefs"
      :rowData="rowData"
      :rowSelection="rowSelection"
      :overlayNoRowsTemplate="overlayNoRowsTemplate"
      @gridReady="onGridReady($event)"
      @rowClicked="rowClicked($event)"
    ></ag-grid-vue>
  </div>
</template>

<script>
import { AgGridVue } from "ag-grid-vue";
export default {
  components: {
    AgGridVue,
  },
  data() {
    return {
      //variables for grid
      gridApi: null,
      rowData: [],
      columnDefs: [],
      rowSelection: "single",
      columnFilter: "",
      overlayNoRowsTemplate: "",
      //variables for component
      returnObject: false,
      clearInputValue: false,
      cellValue: "",
      filteredRowData: null,
      inputValue: "",
      useApi: false,
      apiEndpoint: null,
      queryChars: 2,
      gridHeight: 175,
      gridWidth: 375,
      propertyName: null,
      isCanceled: true,
      selectedObject: {},
      //variables for element
      input: null,
    };
  },
  methods: {
    onGridReady(event) {
      this.gridApi = event.api;
      this.gridApi.sizeColumnsToFit();
      this.columnFilter = this.gridApi.getFilterInstance(this.propertyName);
    },
    rowClicked(event) {
      this.selectedObject = event.data;
      this.isCanceled = false;
      this.params.api.stopEditing();
      this.$refs.autoCompleteArea.removeEventListener("keydown", () => null);
    },
    rowConfirmed() {
      if (this.gridApi.getSelectedRows()[0]) {
        this.selectedObject = this.gridApi.getSelectedRows()[0];
        this.isCanceled = false;
      }
      this.params.api.stopEditing();
      this.$refs.autoCompleteArea.removeEventListener("keydown", () => null);
    },
    onKeydown(event) {
      event.stopPropagation();
      if (event.key === "Escape") {
        this.params.api.stopEditing();
        this.$refs.autoCompleteArea.removeEventListener("keydown", () => null);

        return false;
      }
      if (event.key === "Enter" || event.key === "Tab") {
        this.rowConfirmed();
        return false;
      }
      if (event.key === "ArrowUp" || event.key === "ArrowDown") {
        this.navigateGrid();
        return false;
      }
    },
    processDataInput(inputValue) {
      if (this.useApi === true) {
        if (inputValue.length < this.queryChars) {
          this.gridApi.setRowData([]);
        }
        if (inputValue.length === this.queryChars) {
          this.getApiData(inputValue).then((data) => {
            this.rowData = data.data;
            window.setTimeout(() => {
              this.updateFilter();
            });
          });
        }
        if (inputValue.length > this.queryChars) this.updateFilter();
      } else {
        this.updateFilter();
      }
    },
    getApiData(filter) {
      return this.$axios.get(this.apiEndpoint + this.toQueryString(filter));
    },
    toQueryString(filter) {
      return "?" + this.propertyName + "=" + filter;
    },
    updateFilter() {
      if (this.columnFilter && this.gridApi) {
        this.columnFilter.setModel({
          type: "startsWith",
          filter: this.inputValue,
        });
        this.columnFilter.onFilterChanged();

        if (this.gridApi.getDisplayedRowAtIndex(0)) {
          this.gridApi.getDisplayedRowAtIndex(0).setSelected(true);
          this.gridApi.ensureIndexVisible(0, "top");
        } else {
          this.gridApi.deselectAll();
        }
      }
    },
    navigateGrid() {
      if (
        this.gridApi.getFocusedCell() == null ||
        this.gridApi.getDisplayedRowAtIndex(
          this.gridApi.getFocusedCell().rowIndex
        ) == null
      ) {
        // check if no cell has focus, or if focused cell is filtered
        this.gridApi.setFocusedCell(
          this.gridApi.getDisplayedRowAtIndex(0).rowIndex,
          this.propertyName
        );
        this.gridApi
          .getDisplayedRowAtIndex(this.gridApi.getFocusedCell().rowIndex)
          .setSelected(true);
      } else {
        this.gridApi.setFocusedCell(
          this.gridApi.getFocusedCell().rowIndex,
          this.propertyName
        );
        this.gridApi
          .getDisplayedRowAtIndex(this.gridApi.getFocusedCell().rowIndex)
          .setSelected(true);
      }
    },
    overLayLoading() {
      return '<span class="ag-overlay-no-rows-center">No rows to be shown. <br> Loading...</span>';
    },
    overLayMinimumCharacters() {
      return `<span class="ag-overlay-no-rows-center">No rows to be shown. <br> This search field requires at least ${this.queryChars} characters.</span>`;
    },
  },
  beforeMount() {
    if (!this.params.rowData) {
      this.apiEndpoint = this.params.apiEndpoint;
      this.useApi = true;
    } else {
      this.rowData = this.params.rowData;
    }
    if (this.params.gridHeight) this.gridHeight = this.params.gridHeight;
    if (this.params.gridWidth) this.gridWidth = this.params.gridWidth;

    if (this.params.queryChars > -1) this.queryChars = this.params.queryChars;
    this.columnDefs = this.params.columnDefs;
    this.propertyName = this.params.propertyRendered;
    this.returnObject = this.params.returnObject;
    this.clearInputValue = this.params.clearInputValue;

    this.cellValue =
      this.params.propertyRendered === "" ||
      this.params.returnObject === false ||
      this.params.value == null
        ? ""
        : this.params.value[this.propertyName];

    if (this.queryChars === 0) {
      this.overlayNoRowsTemplate = this.overLayLoading();
    } else {
      this.overlayNoRowsTemplate = this.overLayMinimumCharacters();
    }

    if (!this.params.charPress) {
      if (this.cellValue != null && !this.clearInputValue)
        this.inputValue = this.cellValue;
    } else {
      this.inputValue = this.params.charPress;
    }

    if (
      this.useApi === true &&
      (this.queryChars === 0 ||
        (this.inputValue != null &&
          this.inputValue !== "" &&
          this.inputValue.length > this.queryChars))
    ) {
      this.getApiData(this.inputValue).then((data) => {
        this.rowData = data.data;
        window.setTimeout(() => {
          this.updateFilter();
        });
      });
    }
  },
  mounted() {
    this.input = this.$refs.input;

    this.$nextTick(() => {
      this.inputValue === this.cellValue
        ? this.input.select()
        : this.input.focus();
      if (this.inputValue && !this.useApi) this.updateFilter();
    });

    this.gridComponent.getValue = () => {
      if (!this.returnObject) return this.selectedObject[this.propertyName];
      return this.selectedObject;
    };

    this.gridComponent.isPopup = () => {
      return true;
    };
    this.gridComponent.isCancelAfterEnd = () => {
      return this.isCanceled;
    };

    this.$refs.autoCompleteArea.addEventListener("keydown", this.onKeydown);
  },
  watch: {
    inputValue(val) {
      this.processDataInput(val);
    },
  },
};
</script>
