import Vue from "vue";

import VAutoComplete from "./AutoComplete.vue";

export default function getAutoComplete() {
  function AutoComplete() {
  }

  AutoComplete.prototype.init = function (params) {
    this.el = new Vue({
      mixins: [
        Vue.mixin({
          data() {
            return {
              gridComponent: AutoComplete.prototype,
              params,
            };
          },
        }),
      ],

      render(h) {
        return h(VAutoComplete);
      },
    }).$mount().$el;
  };
  AutoComplete.prototype.getGui = function () {
    return this.el;
  };
  return AutoComplete;
}
