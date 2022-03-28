export { default as Attachment } from '../..\\nuxt\\components\\Attachment.vue'
export { default as DialogForm } from '../..\\nuxt\\components\\DialogForm.vue'
export { default as DialogMaster } from '../..\\nuxt\\components\\DialogMaster.vue'
export { default as Editor } from '../..\\nuxt\\components\\Editor.vue'
export { default as Footer } from '../..\\nuxt\\components\\Footer.vue'
export { default as MainToolbar } from '../..\\nuxt\\components\\MainToolbar.vue'
export { default as Snackbar } from '../..\\nuxt\\components\\Snackbar.vue'
export { default as SpinnerLoading } from '../..\\nuxt\\components\\SpinnerLoading.vue'
export { default as TableFilter } from '../..\\nuxt\\components\\TableFilter.vue'
export { default as TableSimple } from '../..\\nuxt\\components\\TableSimple.vue'
export { default as ToolbarTitle } from '../..\\nuxt\\components\\ToolbarTitle.vue'
export { default as VideoCard } from '../..\\nuxt\\components\\VideoCard.vue'
export { default as DashboardCheck } from '../..\\nuxt\\components\\Dashboard\\Check.vue'
export { default as DashboardForm } from '../..\\nuxt\\components\\Dashboard\\Form.vue'
export { default as DocumentDialogFormDocument } from '../..\\nuxt\\components\\Document\\DialogFormDocument.vue'
export { default as DocumentDialogSendEmail } from '../..\\nuxt\\components\\Document\\DialogSendEmail.vue'
export { default as DocumentFieldUpload } from '../..\\nuxt\\components\\Document\\FieldUpload.vue'
export { default as DocumentFormInput } from '../..\\nuxt\\components\\Document\\FormInput.vue'
export { default as DocumentFormWindow } from '../..\\nuxt\\components\\Document\\FormWindow.vue'
export { default as DocumentTableDetail } from '../..\\nuxt\\components\\Document\\TableDetail.vue'
export { default as DocumentTableDocument } from '../..\\nuxt\\components\\Document\\TableDocument.vue'
export { default as DocumentTableItem } from '../..\\nuxt\\components\\Document\\TableItem.vue'
export { default as FinancialFormAccount } from '../..\\nuxt\\components\\Financial\\FormAccount.vue'
export { default as MasterDialogPermission } from '../..\\nuxt\\components\\Master\\DialogPermission.vue'
export { default as MasterPermissionList } from '../..\\nuxt\\components\\Master\\PermissionList.vue'
export { default as InventoryDialogItem } from '../..\\nuxt\\components\\Inventory\\DialogItem.vue'
export { default as InventoryFormContact } from '../..\\nuxt\\components\\Inventory\\FormContact.vue'
export { default as InventoryFormItem } from '../..\\nuxt\\components\\Inventory\\FormItem.vue'
export { default as InventoryFormMaster } from '../..\\nuxt\\components\\Inventory\\FormMaster.vue'
export { default as InventoryTableContact } from '../..\\nuxt\\components\\Inventory\\TableContact.vue'
export { default as InventoryTableItem } from '../..\\nuxt\\components\\Inventory\\TableItem.vue'
export { default as SetupAccountMapping } from '../..\\nuxt\\components\\Setup\\AccountMapping.vue'
export { default as SetupCompany } from '../..\\nuxt\\components\\Setup\\Company.vue'
export { default as SetupCurrency } from '../..\\nuxt\\components\\Setup\\Currency.vue'
export { default as SetupEmail } from '../..\\nuxt\\components\\Setup\\Email.vue'
export { default as SetupFinance } from '../..\\nuxt\\components\\Setup\\Finance.vue'
export { default as SetupForm } from '../..\\nuxt\\components\\Setup\\Form.vue'
export { default as SetupGeneral } from '../..\\nuxt\\components\\Setup\\General.vue'
export { default as SetupPaymentMethod } from '../..\\nuxt\\components\\Setup\\PaymentMethod.vue'
export { default as SetupPaymentTerm } from '../..\\nuxt\\components\\Setup\\PaymentTerm.vue'
export { default as SetupPdf } from '../..\\nuxt\\components\\Setup\\Pdf.vue'
export { default as SetupTax } from '../..\\nuxt\\components\\Setup\\Tax.vue'
export { default as StudentDataHelp } from '../..\\nuxt\\components\\Student\\DataHelp.vue'
export { default as StudentFormStudent } from '../..\\nuxt\\components\\Student\\FormStudent.vue'
export { default as StudentListDocument } from '../..\\nuxt\\components\\Student\\ListDocument.vue'
export { default as StudentParentInfo } from '../..\\nuxt\\components\\Student\\ParentInfo.vue'
export { default as StudentPersonalInfo } from '../..\\nuxt\\components\\Student\\PersonalInfo.vue'
export { default as StudentScoreInfo } from '../..\\nuxt\\components\\Student\\ScoreInfo.vue'
export { default as TasksFormTask } from '../..\\nuxt\\components\\Tasks\\FormTask.vue'

// nuxt/nuxt.js#8607
function wrapFunctional(options) {
  if (!options || !options.functional) {
    return options
  }

  const propKeys = Array.isArray(options.props) ? options.props : Object.keys(options.props || {})

  return {
    render(h) {
      const attrs = {}
      const props = {}

      for (const key in this.$attrs) {
        if (propKeys.includes(key)) {
          props[key] = this.$attrs[key]
        } else {
          attrs[key] = this.$attrs[key]
        }
      }

      return h(options, {
        on: this.$listeners,
        attrs,
        props,
        scopedSlots: this.$scopedSlots,
      }, this.$slots.default)
    }
  }
}
