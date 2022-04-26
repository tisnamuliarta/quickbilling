<template>
  <div ref="sheetEl"/>
</template>
<script>
import jspreadsheet from "jspreadsheet";
import "jspreadsheet/dist/jspreadsheet.css";
import "jsuites/dist/jsuites.css";

export default {
  name: "Jspreadsheet",
  props: {
    options: {
      type: Object,
      require: true,
    },
  },
  mounted: function () {
    const options = this.options ? {...this.options} : {};
    let spreadsheet = jexcel(this.$el, options)
    Object.assign(this, { spreadsheet })
  },
  setup(props) {
    const options = props.options ? {...props.options} : {};
    const sheetEl = ref(null);
    onMounted(() => {
      jspreadsheet(sheetEl.value, options);
    });
    return {sheetEl};
  },
};
</script
