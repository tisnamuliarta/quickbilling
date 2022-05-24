import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _72c2a8fb = () => interopDefault(import('..\\nuxt\\pages\\list\\index.vue' /* webpackChunkName: "pages/list/index" */))
const _047989b0 = () => interopDefault(import('..\\nuxt\\pages\\app\\contact.vue' /* webpackChunkName: "pages/app/contact" */))
const _cab7f940 = () => interopDefault(import('..\\nuxt\\pages\\app\\expense.vue' /* webpackChunkName: "pages/app/expense" */))
const _3256e3a4 = () => interopDefault(import('..\\nuxt\\pages\\app\\expense\\form.vue' /* webpackChunkName: "pages/app/expense/form" */))
const _6a18648a = () => interopDefault(import('..\\nuxt\\pages\\app\\item.vue' /* webpackChunkName: "pages/app/item" */))
const _eedd1670 = () => interopDefault(import('..\\nuxt\\pages\\auth\\login.vue' /* webpackChunkName: "pages/auth/login" */))
const _fe50f0fa = () => interopDefault(import('..\\nuxt\\pages\\documents\\form.vue' /* webpackChunkName: "pages/documents/form" */))
const _965ea346 = () => interopDefault(import('..\\nuxt\\pages\\documents\\list.vue' /* webpackChunkName: "pages/documents/list" */))
const _a83a4638 = () => interopDefault(import('..\\nuxt\\pages\\documents\\view.vue' /* webpackChunkName: "pages/documents/view" */))
const _7ab448a8 = () => interopDefault(import('..\\nuxt\\pages\\list\\accountmapping.vue' /* webpackChunkName: "pages/list/accountmapping" */))
const _a2b8a36c = () => interopDefault(import('..\\nuxt\\pages\\list\\attachment.vue' /* webpackChunkName: "pages/list/attachment" */))
const _c560e234 = () => interopDefault(import('..\\nuxt\\pages\\list\\company.vue' /* webpackChunkName: "pages/list/company" */))
const _141e5410 = () => interopDefault(import('..\\nuxt\\pages\\list\\currency.vue' /* webpackChunkName: "pages/list/currency" */))
const _75d8e3c6 = () => interopDefault(import('..\\nuxt\\pages\\list\\menu.vue' /* webpackChunkName: "pages/list/menu" */))
const _73e919e0 = () => interopDefault(import('..\\nuxt\\pages\\list\\paymentmethod.vue' /* webpackChunkName: "pages/list/paymentmethod" */))
const _52fb7a4a = () => interopDefault(import('..\\nuxt\\pages\\list\\paymentterm.vue' /* webpackChunkName: "pages/list/paymentterm" */))
const _1e174a26 = () => interopDefault(import('..\\nuxt\\pages\\list\\permissions.vue' /* webpackChunkName: "pages/list/permissions" */))
const _82587df4 = () => interopDefault(import('..\\nuxt\\pages\\list\\recurring.vue' /* webpackChunkName: "pages/list/recurring" */))
const _9011bd34 = () => interopDefault(import('..\\nuxt\\pages\\list\\roles.vue' /* webpackChunkName: "pages/list/roles" */))
const _007625b4 = () => interopDefault(import('..\\nuxt\\pages\\list\\setup.vue' /* webpackChunkName: "pages/list/setup" */))
const _2da29b94 = () => interopDefault(import('..\\nuxt\\pages\\list\\tax.vue' /* webpackChunkName: "pages/list/tax" */))
const _8fa6f99e = () => interopDefault(import('..\\nuxt\\pages\\list\\users.vue' /* webpackChunkName: "pages/list/users" */))
const _613cd702 = () => interopDefault(import('..\\nuxt\\pages\\reports\\bank.vue' /* webpackChunkName: "pages/reports/bank" */))
const _d1e290d8 = () => interopDefault(import('..\\nuxt\\pages\\reports\\bp.vue' /* webpackChunkName: "pages/reports/bp" */))
const _163c0859 = () => interopDefault(import('..\\nuxt\\pages\\reports\\item.vue' /* webpackChunkName: "pages/reports/item" */))
const _6ea6c929 = () => interopDefault(import('..\\nuxt\\pages\\tools\\audit.vue' /* webpackChunkName: "pages/tools/audit" */))
const _1068f2d6 = () => interopDefault(import('..\\nuxt\\pages\\tools\\export.vue' /* webpackChunkName: "pages/tools/export" */))
const _fac41b72 = () => interopDefault(import('..\\nuxt\\pages\\tools\\import.vue' /* webpackChunkName: "pages/tools/import" */))
const _3e4c7280 = () => interopDefault(import('..\\nuxt\\pages\\app\\accounting\\account.vue' /* webpackChunkName: "pages/app/accounting/account" */))
const _394bfe1b = () => interopDefault(import('..\\nuxt\\pages\\app\\accounting\\account-category.vue' /* webpackChunkName: "pages/app/accounting/account-category" */))
const _63096f60 = () => interopDefault(import('..\\nuxt\\pages\\app\\accounting\\budgeting.vue' /* webpackChunkName: "pages/app/accounting/budgeting" */))
const _8f1fd570 = () => interopDefault(import('..\\nuxt\\pages\\app\\accounting\\closing.vue' /* webpackChunkName: "pages/app/accounting/closing" */))
const _330f69a2 = () => interopDefault(import('..\\nuxt\\pages\\app\\accounting\\journal-entry.vue' /* webpackChunkName: "pages/app/accounting/journal-entry" */))
const _ea6ecc42 = () => interopDefault(import('..\\nuxt\\pages\\app\\accounting\\price-list.vue' /* webpackChunkName: "pages/app/accounting/price-list" */))
const _75bc2286 = () => interopDefault(import('..\\nuxt\\pages\\app\\accounting\\reporting-period.vue' /* webpackChunkName: "pages/app/accounting/reporting-period" */))
const _20216a6a = () => interopDefault(import('..\\nuxt\\pages\\app\\bank\\deposit.vue' /* webpackChunkName: "pages/app/bank/deposit" */))
const _318e2bcc = () => interopDefault(import('..\\nuxt\\pages\\app\\bank\\reconcile.vue' /* webpackChunkName: "pages/app/bank/reconcile" */))
const _5583634f = () => interopDefault(import('..\\nuxt\\pages\\app\\bank\\transfer.vue' /* webpackChunkName: "pages/app/bank/transfer" */))
const _36730eee = () => interopDefault(import('..\\nuxt\\pages\\app\\bank\\withdraw.vue' /* webpackChunkName: "pages/app/bank/withdraw" */))
const _67434cdc = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\creditmemo.vue' /* webpackChunkName: "pages/app/purchase/creditmemo" */))
const _12202020 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\creditmemo\\form.vue' /* webpackChunkName: "pages/app/purchase/creditmemo/form" */))
const _d36b6b96 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\dp.vue' /* webpackChunkName: "pages/app/purchase/dp" */))
const _513ff6b9 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\dp\\form.vue' /* webpackChunkName: "pages/app/purchase/dp/form" */))
const _671c2e58 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\invoice.vue' /* webpackChunkName: "pages/app/purchase/invoice" */))
const _01b7e218 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\invoice\\form.vue' /* webpackChunkName: "pages/app/purchase/invoice/form" */))
const _ad9d6796 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\order.vue' /* webpackChunkName: "pages/app/purchase/order" */))
const _275d78b9 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\order\\form.vue' /* webpackChunkName: "pages/app/purchase/order/form" */))
const _b7fa79a6 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\payment.vue' /* webpackChunkName: "pages/app/purchase/payment" */))
const _5fc7d5b1 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\payment\\form.vue' /* webpackChunkName: "pages/app/purchase/payment/form" */))
const _6823bc63 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\quote.vue' /* webpackChunkName: "pages/app/purchase/quote" */))
const _b7cae332 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\quote\\form.vue' /* webpackChunkName: "pages/app/purchase/quote/form" */))
const _79099a5f = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\receipt.vue' /* webpackChunkName: "pages/app/purchase/receipt" */))
const _edee013a = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\receipt\\form.vue' /* webpackChunkName: "pages/app/purchase/receipt/form" */))
const _bb92cf4e = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\return.vue' /* webpackChunkName: "pages/app/purchase/return" */))
const _529a19dd = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\return\\form.vue' /* webpackChunkName: "pages/app/purchase/return/form" */))
const _261281c7 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\creditmemo.vue' /* webpackChunkName: "pages/app/sales/creditmemo" */))
const _e5de8e6a = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\creditmemo\\form.vue' /* webpackChunkName: "pages/app/sales/creditmemo/form" */))
const _f4cfeef0 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\delivery.vue' /* webpackChunkName: "pages/app/sales/delivery" */))
const _4d272a68 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\delivery\\form.vue' /* webpackChunkName: "pages/app/sales/delivery/form" */))
const _cff2cbc0 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\dp.vue' /* webpackChunkName: "pages/app/sales/dp" */))
const _9cc52b38 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\dp\\form.vue' /* webpackChunkName: "pages/app/sales/dp/form" */))
const _3fdc586e = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\invoice.vue' /* webpackChunkName: "pages/app/sales/invoice" */))
const _96d1fd66 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\invoice\\form.vue' /* webpackChunkName: "pages/app/sales/invoice/form" */))
const _bb6d1f2c = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\order.vue' /* webpackChunkName: "pages/app/sales/order" */))
const _4274e12e = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\order\\form.vue' /* webpackChunkName: "pages/app/sales/order/form" */))
const _90baa3bc = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\payment.vue' /* webpackChunkName: "pages/app/sales/payment" */))
const _12a6f4e6 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\payment\\form.vue' /* webpackChunkName: "pages/app/sales/payment/form" */))
const _613be098 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\quote.vue' /* webpackChunkName: "pages/app/sales/quote" */))
const _819c1248 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\quote\\form.vue' /* webpackChunkName: "pages/app/sales/quote/form" */))
const _67ba0a78 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\return.vue' /* webpackChunkName: "pages/app/sales/return" */))
const _cb207ff0 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\return\\form.vue' /* webpackChunkName: "pages/app/sales/return/form" */))
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
    path: "/list",
    component: _72c2a8fb,
    name: "list"
  }, {
    path: "/app/contact",
    component: _047989b0,
    name: "app-contact"
  }, {
    path: "/app/expense",
    component: _cab7f940,
    name: "app-expense",
    children: [{
      path: "form",
      component: _3256e3a4,
      name: "app-expense-form"
    }]
  }, {
    path: "/app/item",
    component: _6a18648a,
    name: "app-item"
  }, {
    path: "/auth/login",
    component: _eedd1670,
    name: "auth-login"
  }, {
    path: "/documents/form",
    component: _fe50f0fa,
    name: "documents-form"
  }, {
    path: "/documents/list",
    component: _965ea346,
    name: "documents-list"
  }, {
    path: "/documents/view",
    component: _a83a4638,
    name: "documents-view"
  }, {
    path: "/list/accountmapping",
    component: _7ab448a8,
    name: "list-accountmapping"
  }, {
    path: "/list/attachment",
    component: _a2b8a36c,
    name: "list-attachment"
  }, {
    path: "/list/company",
    component: _c560e234,
    name: "list-company"
  }, {
    path: "/list/currency",
    component: _141e5410,
    name: "list-currency"
  }, {
    path: "/list/menu",
    component: _75d8e3c6,
    name: "list-menu"
  }, {
    path: "/list/paymentmethod",
    component: _73e919e0,
    name: "list-paymentmethod"
  }, {
    path: "/list/paymentterm",
    component: _52fb7a4a,
    name: "list-paymentterm"
  }, {
    path: "/list/permissions",
    component: _1e174a26,
    name: "list-permissions"
  }, {
    path: "/list/recurring",
    component: _82587df4,
    name: "list-recurring"
  }, {
    path: "/list/roles",
    component: _9011bd34,
    name: "list-roles"
  }, {
    path: "/list/setup",
    component: _007625b4,
    name: "list-setup"
  }, {
    path: "/list/tax",
    component: _2da29b94,
    name: "list-tax"
  }, {
    path: "/list/users",
    component: _8fa6f99e,
    name: "list-users"
  }, {
    path: "/reports/bank",
    component: _613cd702,
    name: "reports-bank"
  }, {
    path: "/reports/bp",
    component: _d1e290d8,
    name: "reports-bp"
  }, {
    path: "/reports/item",
    component: _163c0859,
    name: "reports-item"
  }, {
    path: "/tools/audit",
    component: _6ea6c929,
    name: "tools-audit"
  }, {
    path: "/tools/export",
    component: _1068f2d6,
    name: "tools-export"
  }, {
    path: "/tools/import",
    component: _fac41b72,
    name: "tools-import"
  }, {
    path: "/app/accounting/account",
    component: _3e4c7280,
    name: "app-accounting-account"
  }, {
    path: "/app/accounting/account-category",
    component: _394bfe1b,
    name: "app-accounting-account-category"
  }, {
    path: "/app/accounting/budgeting",
    component: _63096f60,
    name: "app-accounting-budgeting"
  }, {
    path: "/app/accounting/closing",
    component: _8f1fd570,
    name: "app-accounting-closing"
  }, {
    path: "/app/accounting/journal-entry",
    component: _330f69a2,
    name: "app-accounting-journal-entry"
  }, {
    path: "/app/accounting/price-list",
    component: _ea6ecc42,
    name: "app-accounting-price-list"
  }, {
    path: "/app/accounting/reporting-period",
    component: _75bc2286,
    name: "app-accounting-reporting-period"
  }, {
    path: "/app/bank/deposit",
    component: _20216a6a,
    name: "app-bank-deposit"
  }, {
    path: "/app/bank/reconcile",
    component: _318e2bcc,
    name: "app-bank-reconcile"
  }, {
    path: "/app/bank/transfer",
    component: _5583634f,
    name: "app-bank-transfer"
  }, {
    path: "/app/bank/withdraw",
    component: _36730eee,
    name: "app-bank-withdraw"
  }, {
    path: "/app/purchase/creditmemo",
    component: _67434cdc,
    name: "app-purchase-creditmemo",
    children: [{
      path: "form",
      component: _12202020,
      name: "app-purchase-creditmemo-form"
    }]
  }, {
    path: "/app/purchase/dp",
    component: _d36b6b96,
    name: "app-purchase-dp",
    children: [{
      path: "form",
      component: _513ff6b9,
      name: "app-purchase-dp-form"
    }]
  }, {
    path: "/app/purchase/invoice",
    component: _671c2e58,
    name: "app-purchase-invoice",
    children: [{
      path: "form",
      component: _01b7e218,
      name: "app-purchase-invoice-form"
    }]
  }, {
    path: "/app/purchase/order",
    component: _ad9d6796,
    name: "app-purchase-order",
    children: [{
      path: "form",
      component: _275d78b9,
      name: "app-purchase-order-form"
    }]
  }, {
    path: "/app/purchase/payment",
    component: _b7fa79a6,
    name: "app-purchase-payment",
    children: [{
      path: "form",
      component: _5fc7d5b1,
      name: "app-purchase-payment-form"
    }]
  }, {
    path: "/app/purchase/quote",
    component: _6823bc63,
    name: "app-purchase-quote",
    children: [{
      path: "form",
      component: _b7cae332,
      name: "app-purchase-quote-form"
    }]
  }, {
    path: "/app/purchase/receipt",
    component: _79099a5f,
    name: "app-purchase-receipt",
    children: [{
      path: "form",
      component: _edee013a,
      name: "app-purchase-receipt-form"
    }]
  }, {
    path: "/app/purchase/return",
    component: _bb92cf4e,
    name: "app-purchase-return",
    children: [{
      path: "form",
      component: _529a19dd,
      name: "app-purchase-return-form"
    }]
  }, {
    path: "/app/sales/creditmemo",
    component: _261281c7,
    name: "app-sales-creditmemo",
    children: [{
      path: "form",
      component: _e5de8e6a,
      name: "app-sales-creditmemo-form"
    }]
  }, {
    path: "/app/sales/delivery",
    component: _f4cfeef0,
    name: "app-sales-delivery",
    children: [{
      path: "form",
      component: _4d272a68,
      name: "app-sales-delivery-form"
    }]
  }, {
    path: "/app/sales/dp",
    component: _cff2cbc0,
    name: "app-sales-dp",
    children: [{
      path: "form",
      component: _9cc52b38,
      name: "app-sales-dp-form"
    }]
  }, {
    path: "/app/sales/invoice",
    component: _3fdc586e,
    name: "app-sales-invoice",
    children: [{
      path: "form",
      component: _96d1fd66,
      name: "app-sales-invoice-form"
    }]
  }, {
    path: "/app/sales/order",
    component: _bb6d1f2c,
    name: "app-sales-order",
    children: [{
      path: "form",
      component: _4274e12e,
      name: "app-sales-order-form"
    }]
  }, {
    path: "/app/sales/payment",
    component: _90baa3bc,
    name: "app-sales-payment",
    children: [{
      path: "form",
      component: _12a6f4e6,
      name: "app-sales-payment-form"
    }]
  }, {
    path: "/app/sales/quote",
    component: _613be098,
    name: "app-sales-quote",
    children: [{
      path: "form",
      component: _819c1248,
      name: "app-sales-quote-form"
    }]
  }, {
    path: "/app/sales/return",
    component: _67ba0a78,
    name: "app-sales-return",
    children: [{
      path: "form",
      component: _cb207ff0,
      name: "app-sales-return-form"
    }]
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
