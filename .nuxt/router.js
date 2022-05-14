import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _0afc7872 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\index.vue' /* webpackChunkName: "pages/dashboard/index" */))
const _294c82b4 = () => interopDefault(import('..\\nuxt\\pages\\app\\deposit.vue' /* webpackChunkName: "pages/app/deposit" */))
const _84efec7a = () => interopDefault(import('..\\nuxt\\pages\\app\\expenses.vue' /* webpackChunkName: "pages/app/expenses" */))
const _718b0fe4 = () => interopDefault(import('..\\nuxt\\pages\\app\\goodsreturn.vue' /* webpackChunkName: "pages/app/goodsreturn" */))
const _2f419390 = () => interopDefault(import('..\\nuxt\\pages\\app\\invtoryqtyadjustment.vue' /* webpackChunkName: "pages/app/invtoryqtyadjustment" */))
const _0c113a82 = () => interopDefault(import('..\\nuxt\\pages\\app\\journal.vue' /* webpackChunkName: "pages/app/journal" */))
const _cb469892 = () => interopDefault(import('..\\nuxt\\pages\\app\\outgoingpaymment.vue' /* webpackChunkName: "pages/app/outgoingpaymment" */))
const _166f519c = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasecreditmemo.vue' /* webpackChunkName: "pages/app/purchasecreditmemo" */))
const _40921c14 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchaseinvoice.vue' /* webpackChunkName: "pages/app/purchaseinvoice" */))
const _12454f75 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchaseorder.vue' /* webpackChunkName: "pages/app/purchaseorder" */))
const _7683ba94 = () => interopDefault(import('..\\nuxt\\pages\\app\\quotation.vue' /* webpackChunkName: "pages/app/quotation" */))
const _76ccc547 = () => interopDefault(import('..\\nuxt\\pages\\app\\salescreditmemo.vue' /* webpackChunkName: "pages/app/salescreditmemo" */))
const _5f0d2c08 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesdelivery.vue' /* webpackChunkName: "pages/app/salesdelivery" */))
const _39aa4049 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesinvoice.vue' /* webpackChunkName: "pages/app/salesinvoice" */))
const _4812fcea = () => interopDefault(import('..\\nuxt\\pages\\app\\salesorder.vue' /* webpackChunkName: "pages/app/salesorder" */))
const _113b1aa2 = () => interopDefault(import('..\\nuxt\\pages\\app\\salespayment.vue' /* webpackChunkName: "pages/app/salespayment" */))
const _40ea0378 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesreturn.vue' /* webpackChunkName: "pages/app/salesreturn" */))
const _7f8cc9e8 = () => interopDefault(import('..\\nuxt\\pages\\app\\statements.vue' /* webpackChunkName: "pages/app/statements" */))
const _1d54d2da = () => interopDefault(import('..\\nuxt\\pages\\app\\transfer.vue' /* webpackChunkName: "pages/app/transfer" */))
const _eedd1670 = () => interopDefault(import('..\\nuxt\\pages\\auth\\login.vue' /* webpackChunkName: "pages/auth/login" */))
const _be3e61ae = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\index.vue' /* webpackChunkName: "pages/dashboard/list/index" */))
const _a83a4638 = () => interopDefault(import('..\\nuxt\\pages\\documents\\view.vue' /* webpackChunkName: "pages/documents/view" */))
const _0aeed547 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\bp\\contact.vue' /* webpackChunkName: "pages/dashboard/bp/contact" */))
const _ec0a329e = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\documents\\form.vue' /* webpackChunkName: "pages/dashboard/documents/form" */))
const _8417e4ea = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\documents\\list.vue' /* webpackChunkName: "pages/dashboard/documents/list" */))
const _64a22ac2 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\account.vue' /* webpackChunkName: "pages/dashboard/financial/account" */))
const _7af075c8 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\account-category.vue' /* webpackChunkName: "pages/dashboard/financial/account-category" */))
const _60f3f7ea = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\bank-deposit.vue' /* webpackChunkName: "pages/dashboard/financial/bank-deposit" */))
const _da9c0464 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\bank-transfer.vue' /* webpackChunkName: "pages/dashboard/financial/bank-transfer" */))
const _73a1a96d = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\bank-withdraw.vue' /* webpackChunkName: "pages/dashboard/financial/bank-withdraw" */))
const _4ad21f22 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\budgeting.vue' /* webpackChunkName: "pages/dashboard/financial/budgeting" */))
const _b5758db2 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\closing.vue' /* webpackChunkName: "pages/dashboard/financial/closing" */))
const _395f1b4a = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\expense.vue' /* webpackChunkName: "pages/dashboard/financial/expense" */))
const _a3966864 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\journal-entry.vue' /* webpackChunkName: "pages/dashboard/financial/journal-entry" */))
const _bbfc3680 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\reconcile.vue' /* webpackChunkName: "pages/dashboard/financial/reconcile" */))
const _4e5db5be = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\reporting-period.vue' /* webpackChunkName: "pages/dashboard/financial/reporting-period" */))
const _6cf741b8 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\inventory\\item.vue' /* webpackChunkName: "pages/dashboard/inventory/item" */))
const _406c2c73 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\inventory\\price-list.vue' /* webpackChunkName: "pages/dashboard/inventory/price-list" */))
const _1a53363a = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\accountmapping.vue' /* webpackChunkName: "pages/dashboard/list/accountmapping" */))
const _6c279648 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\attachment.vue' /* webpackChunkName: "pages/dashboard/list/attachment" */))
const _87063cd8 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\company.vue' /* webpackChunkName: "pages/dashboard/list/company" */))
const _87244dec = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\currency.vue' /* webpackChunkName: "pages/dashboard/list/currency" */))
const _8d338050 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\menu.vue' /* webpackChunkName: "pages/dashboard/list/menu" */))
const _7a20e984 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\paymentmethod.vue' /* webpackChunkName: "pages/dashboard/list/paymentmethod" */))
const _244a8e89 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\paymentterm.vue' /* webpackChunkName: "pages/dashboard/list/paymentterm" */))
const _3ebca69b = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\permissions.vue' /* webpackChunkName: "pages/dashboard/list/permissions" */))
const _7011bf98 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\recurring.vue' /* webpackChunkName: "pages/dashboard/list/recurring" */))
const _33d570d8 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\roles.vue' /* webpackChunkName: "pages/dashboard/list/roles" */))
const _2de31354 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\setup.vue' /* webpackChunkName: "pages/dashboard/list/setup" */))
const _2fd07542 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\tax.vue' /* webpackChunkName: "pages/dashboard/list/tax" */))
const _336aad42 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\list\\users.vue' /* webpackChunkName: "pages/dashboard/list/users" */))
const _95d43762 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\creditmemo.vue' /* webpackChunkName: "pages/dashboard/purchase/creditmemo" */))
const _428df6b0 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\dp.vue' /* webpackChunkName: "pages/dashboard/purchase/dp" */))
const _7b36837e = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\invoice.vue' /* webpackChunkName: "pages/dashboard/purchase/invoice" */))
const _9276ce3c = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\order.vue' /* webpackChunkName: "pages/dashboard/purchase/order" */))
const _cc14cecc = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\payment.vue' /* webpackChunkName: "pages/dashboard/purchase/payment" */))
const _75b70910 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\quote.vue' /* webpackChunkName: "pages/dashboard/purchase/quote" */))
const _6efc6fcc = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\receipt.vue' /* webpackChunkName: "pages/dashboard/purchase/receipt" */))
const _470ce14c = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\return.vue' /* webpackChunkName: "pages/dashboard/purchase/return" */))
const _ff2baca0 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\reports\\bank.vue' /* webpackChunkName: "pages/dashboard/reports/bank" */))
const _75a6447c = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\reports\\bp.vue' /* webpackChunkName: "pages/dashboard/reports/bp" */))
const _35695b07 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\reports\\item.vue' /* webpackChunkName: "pages/dashboard/reports/item" */))
const _1c055734 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\creditmemo.vue' /* webpackChunkName: "pages/dashboard/sales/creditmemo" */))
const _d9a95596 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\delivery.vue' /* webpackChunkName: "pages/dashboard/sales/delivery" */))
const _76882c8d = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\dp.vue' /* webpackChunkName: "pages/dashboard/sales/dp" */))
const _70888508 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\invoice.vue' /* webpackChunkName: "pages/dashboard/sales/invoice" */))
const _2a8faa46 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\order.vue' /* webpackChunkName: "pages/dashboard/sales/order" */))
const _c166d056 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\payment.vue' /* webpackChunkName: "pages/dashboard/sales/payment" */))
const _acaac9ea = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\quote.vue' /* webpackChunkName: "pages/dashboard/sales/quote" */))
const _dce8e29e = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\return.vue' /* webpackChunkName: "pages/dashboard/sales/return" */))
const _044d68bb = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tools\\audit.vue' /* webpackChunkName: "pages/dashboard/tools/audit" */))
const _2f964584 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tools\\export.vue' /* webpackChunkName: "pages/dashboard/tools/export" */))
const _bc697616 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tools\\import.vue' /* webpackChunkName: "pages/dashboard/tools/import" */))
const _2c1039ce = () => interopDefault(import('..\\nuxt\\pages\\index.vue' /* webpackChunkName: "pages/index" */))

const emptyFn = () => {}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: '/',
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/dashboard",
    component: _0afc7872,
    name: "dashboard"
  }, {
    path: "/app/deposit",
    component: _294c82b4,
    name: "app-deposit"
  }, {
    path: "/app/expenses",
    component: _84efec7a,
    name: "app-expenses"
  }, {
    path: "/app/goodsreturn",
    component: _718b0fe4,
    name: "app-goodsreturn"
  }, {
    path: "/app/invtoryqtyadjustment",
    component: _2f419390,
    name: "app-invtoryqtyadjustment"
  }, {
    path: "/app/journal",
    component: _0c113a82,
    name: "app-journal"
  }, {
    path: "/app/outgoingpaymment",
    component: _cb469892,
    name: "app-outgoingpaymment"
  }, {
    path: "/app/purchasecreditmemo",
    component: _166f519c,
    name: "app-purchasecreditmemo"
  }, {
    path: "/app/purchaseinvoice",
    component: _40921c14,
    name: "app-purchaseinvoice"
  }, {
    path: "/app/purchaseorder",
    component: _12454f75,
    name: "app-purchaseorder"
  }, {
    path: "/app/quotation",
    component: _7683ba94,
    name: "app-quotation"
  }, {
    path: "/app/salescreditmemo",
    component: _76ccc547,
    name: "app-salescreditmemo"
  }, {
    path: "/app/salesdelivery",
    component: _5f0d2c08,
    name: "app-salesdelivery"
  }, {
    path: "/app/salesinvoice",
    component: _39aa4049,
    name: "app-salesinvoice"
  }, {
    path: "/app/salesorder",
    component: _4812fcea,
    name: "app-salesorder"
  }, {
    path: "/app/salespayment",
    component: _113b1aa2,
    name: "app-salespayment"
  }, {
    path: "/app/salesreturn",
    component: _40ea0378,
    name: "app-salesreturn"
  }, {
    path: "/app/statements",
    component: _7f8cc9e8,
    name: "app-statements"
  }, {
    path: "/app/transfer",
    component: _1d54d2da,
    name: "app-transfer"
  }, {
    path: "/auth/login",
    component: _eedd1670,
    name: "auth-login"
  }, {
    path: "/dashboard/list",
    component: _be3e61ae,
    name: "dashboard-list"
  }, {
    path: "/documents/view",
    component: _a83a4638,
    name: "documents-view"
  }, {
    path: "/dashboard/bp/contact",
    component: _0aeed547,
    name: "dashboard-bp-contact"
  }, {
    path: "/dashboard/documents/form",
    component: _ec0a329e,
    name: "dashboard-documents-form"
  }, {
    path: "/dashboard/documents/list",
    component: _8417e4ea,
    name: "dashboard-documents-list"
  }, {
    path: "/dashboard/financial/account",
    component: _64a22ac2,
    name: "dashboard-financial-account"
  }, {
    path: "/dashboard/financial/account-category",
    component: _7af075c8,
    name: "dashboard-financial-account-category"
  }, {
    path: "/dashboard/financial/bank-deposit",
    component: _60f3f7ea,
    name: "dashboard-financial-bank-deposit"
  }, {
    path: "/dashboard/financial/bank-transfer",
    component: _da9c0464,
    name: "dashboard-financial-bank-transfer"
  }, {
    path: "/dashboard/financial/bank-withdraw",
    component: _73a1a96d,
    name: "dashboard-financial-bank-withdraw"
  }, {
    path: "/dashboard/financial/budgeting",
    component: _4ad21f22,
    name: "dashboard-financial-budgeting"
  }, {
    path: "/dashboard/financial/closing",
    component: _b5758db2,
    name: "dashboard-financial-closing"
  }, {
    path: "/dashboard/financial/expense",
    component: _395f1b4a,
    name: "dashboard-financial-expense"
  }, {
    path: "/dashboard/financial/journal-entry",
    component: _a3966864,
    name: "dashboard-financial-journal-entry"
  }, {
    path: "/dashboard/financial/reconcile",
    component: _bbfc3680,
    name: "dashboard-financial-reconcile"
  }, {
    path: "/dashboard/financial/reporting-period",
    component: _4e5db5be,
    name: "dashboard-financial-reporting-period"
  }, {
    path: "/dashboard/inventory/item",
    component: _6cf741b8,
    name: "dashboard-inventory-item"
  }, {
    path: "/dashboard/inventory/price-list",
    component: _406c2c73,
    name: "dashboard-inventory-price-list"
  }, {
    path: "/dashboard/list/accountmapping",
    component: _1a53363a,
    name: "dashboard-list-accountmapping"
  }, {
    path: "/dashboard/list/attachment",
    component: _6c279648,
    name: "dashboard-list-attachment"
  }, {
    path: "/dashboard/list/company",
    component: _87063cd8,
    name: "dashboard-list-company"
  }, {
    path: "/dashboard/list/currency",
    component: _87244dec,
    name: "dashboard-list-currency"
  }, {
    path: "/dashboard/list/menu",
    component: _8d338050,
    name: "dashboard-list-menu"
  }, {
    path: "/dashboard/list/paymentmethod",
    component: _7a20e984,
    name: "dashboard-list-paymentmethod"
  }, {
    path: "/dashboard/list/paymentterm",
    component: _244a8e89,
    name: "dashboard-list-paymentterm"
  }, {
    path: "/dashboard/list/permissions",
    component: _3ebca69b,
    name: "dashboard-list-permissions"
  }, {
    path: "/dashboard/list/recurring",
    component: _7011bf98,
    name: "dashboard-list-recurring"
  }, {
    path: "/dashboard/list/roles",
    component: _33d570d8,
    name: "dashboard-list-roles"
  }, {
    path: "/dashboard/list/setup",
    component: _2de31354,
    name: "dashboard-list-setup"
  }, {
    path: "/dashboard/list/tax",
    component: _2fd07542,
    name: "dashboard-list-tax"
  }, {
    path: "/dashboard/list/users",
    component: _336aad42,
    name: "dashboard-list-users"
  }, {
    path: "/dashboard/purchase/creditmemo",
    component: _95d43762,
    name: "dashboard-purchase-creditmemo"
  }, {
    path: "/dashboard/purchase/dp",
    component: _428df6b0,
    name: "dashboard-purchase-dp"
  }, {
    path: "/dashboard/purchase/invoice",
    component: _7b36837e,
    name: "dashboard-purchase-invoice"
  }, {
    path: "/dashboard/purchase/order",
    component: _9276ce3c,
    name: "dashboard-purchase-order"
  }, {
    path: "/dashboard/purchase/payment",
    component: _cc14cecc,
    name: "dashboard-purchase-payment"
  }, {
    path: "/dashboard/purchase/quote",
    component: _75b70910,
    name: "dashboard-purchase-quote"
  }, {
    path: "/dashboard/purchase/receipt",
    component: _6efc6fcc,
    name: "dashboard-purchase-receipt"
  }, {
    path: "/dashboard/purchase/return",
    component: _470ce14c,
    name: "dashboard-purchase-return"
  }, {
    path: "/dashboard/reports/bank",
    component: _ff2baca0,
    name: "dashboard-reports-bank"
  }, {
    path: "/dashboard/reports/bp",
    component: _75a6447c,
    name: "dashboard-reports-bp"
  }, {
    path: "/dashboard/reports/item",
    component: _35695b07,
    name: "dashboard-reports-item"
  }, {
    path: "/dashboard/sales/creditmemo",
    component: _1c055734,
    name: "dashboard-sales-creditmemo"
  }, {
    path: "/dashboard/sales/delivery",
    component: _d9a95596,
    name: "dashboard-sales-delivery"
  }, {
    path: "/dashboard/sales/dp",
    component: _76882c8d,
    name: "dashboard-sales-dp"
  }, {
    path: "/dashboard/sales/invoice",
    component: _70888508,
    name: "dashboard-sales-invoice"
  }, {
    path: "/dashboard/sales/order",
    component: _2a8faa46,
    name: "dashboard-sales-order"
  }, {
    path: "/dashboard/sales/payment",
    component: _c166d056,
    name: "dashboard-sales-payment"
  }, {
    path: "/dashboard/sales/quote",
    component: _acaac9ea,
    name: "dashboard-sales-quote"
  }, {
    path: "/dashboard/sales/return",
    component: _dce8e29e,
    name: "dashboard-sales-return"
  }, {
    path: "/dashboard/tools/audit",
    component: _044d68bb,
    name: "dashboard-tools-audit"
  }, {
    path: "/dashboard/tools/export",
    component: _2f964584,
    name: "dashboard-tools-export"
  }, {
    path: "/dashboard/tools/import",
    component: _bc697616,
    name: "dashboard-tools-import"
  }, {
    path: "/",
    component: _2c1039ce,
    name: "index"
  }, {
    path: "/__nuxt_laravel",
    component: _2c1039ce,
    name: "__nuxt_laravel"
  }],

  fallback: false
}

export function createRouter (ssrContext, config) {
  const base = (config._app && config._app.basePath) || routerOptions.base
  const router = new Router({ ...routerOptions, base  })

  // TODO: remove in Nuxt 3
  const originalPush = router.push
  router.push = function push (location, onComplete = emptyFn, onAbort) {
    return originalPush.call(this, location, onComplete, onAbort)
  }

  const resolve = router.resolve.bind(router)
  router.resolve = (to, current, append) => {
    if (typeof to === 'string') {
      to = normalizeURL(to)
    }
    return resolve(to, current, append)
  }

  return router
}
