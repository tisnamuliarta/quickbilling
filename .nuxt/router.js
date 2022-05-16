import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _72c2a8fb = () => interopDefault(import('..\\nuxt\\pages\\list\\index.vue' /* webpackChunkName: "pages/list/index" */))
const _2ef3d2b5 = () => interopDefault(import('..\\nuxt\\pages\\app\\account.vue' /* webpackChunkName: "pages/app/account" */))
const _1a9795c6 = () => interopDefault(import('..\\nuxt\\pages\\app\\account-category.vue' /* webpackChunkName: "pages/app/account-category" */))
const _bac68c96 = () => interopDefault(import('..\\nuxt\\pages\\app\\bank-deposit.vue' /* webpackChunkName: "pages/app/bank-deposit" */))
const _2271fd64 = () => interopDefault(import('..\\nuxt\\pages\\app\\bank-transfer.vue' /* webpackChunkName: "pages/app/bank-transfer" */))
const _0361a903 = () => interopDefault(import('..\\nuxt\\pages\\app\\bank-withdraw.vue' /* webpackChunkName: "pages/app/bank-withdraw" */))
const _037ba9f6 = () => interopDefault(import('..\\nuxt\\pages\\app\\budgeting.vue' /* webpackChunkName: "pages/app/budgeting" */))
const _068a213d = () => interopDefault(import('..\\nuxt\\pages\\app\\closing.vue' /* webpackChunkName: "pages/app/closing" */))
const _047989b0 = () => interopDefault(import('..\\nuxt\\pages\\app\\contact.vue' /* webpackChunkName: "pages/app/contact" */))
const _cab7f940 = () => interopDefault(import('..\\nuxt\\pages\\app\\expense.vue' /* webpackChunkName: "pages/app/expense" */))
const _3256e3a4 = () => interopDefault(import('..\\nuxt\\pages\\app\\expense\\form.vue' /* webpackChunkName: "pages/app/expense/form" */))
const _6a18648a = () => interopDefault(import('..\\nuxt\\pages\\app\\item.vue' /* webpackChunkName: "pages/app/item" */))
const _3df4cb64 = () => interopDefault(import('..\\nuxt\\pages\\app\\journal-entry.vue' /* webpackChunkName: "pages/app/journal-entry" */))
const _53de0dca = () => interopDefault(import('..\\nuxt\\pages\\app\\price-list.vue' /* webpackChunkName: "pages/app/price-list" */))
const _166f519c = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasecreditmemo.vue' /* webpackChunkName: "pages/app/purchasecreditmemo" */))
const _1434d640 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasecreditmemo\\form.vue' /* webpackChunkName: "pages/app/purchasecreditmemo/form" */))
const _49270ef5 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasedp.vue' /* webpackChunkName: "pages/app/purchasedp" */))
const _61262b79 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasedp\\form.vue' /* webpackChunkName: "pages/app/purchasedp/form" */))
const _40921c14 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchaseinvoice.vue' /* webpackChunkName: "pages/app/purchaseinvoice" */))
const _1596b550 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchaseinvoice\\form.vue' /* webpackChunkName: "pages/app/purchaseinvoice/form" */))
const _12454f75 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchaseorder.vue' /* webpackChunkName: "pages/app/purchaseorder" */))
const _5db20bf9 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchaseorder\\form.vue' /* webpackChunkName: "pages/app/purchaseorder/form" */))
const _1822f66d = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasepayment.vue' /* webpackChunkName: "pages/app/purchasepayment" */))
const _534498f1 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasepayment\\form.vue' /* webpackChunkName: "pages/app/purchasepayment/form" */))
const _5d9080ba = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasequote.vue' /* webpackChunkName: "pages/app/purchasequote" */))
const _4b21bcb2 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasequote\\form.vue' /* webpackChunkName: "pages/app/purchasequote/form" */))
const _25ac64c2 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasereceipt.vue' /* webpackChunkName: "pages/app/purchasereceipt" */))
const _7c85c2a3 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasereceipt\\form.vue' /* webpackChunkName: "pages/app/purchasereceipt/form" */))
const _5ba2fd19 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasereturn.vue' /* webpackChunkName: "pages/app/purchasereturn" */))
const _325022c6 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchasereturn\\form.vue' /* webpackChunkName: "pages/app/purchasereturn/form" */))
const _74a5c154 = () => interopDefault(import('..\\nuxt\\pages\\app\\reconcile.vue' /* webpackChunkName: "pages/app/reconcile" */))
const _b324f330 = () => interopDefault(import('..\\nuxt\\pages\\app\\reporting-period.vue' /* webpackChunkName: "pages/app/reporting-period" */))
const _76ccc547 = () => interopDefault(import('..\\nuxt\\pages\\app\\salescreditmemo.vue' /* webpackChunkName: "pages/app/salescreditmemo" */))
const _c22b476a = () => interopDefault(import('..\\nuxt\\pages\\app\\salescreditmemo\\form.vue' /* webpackChunkName: "pages/app/salescreditmemo/form" */))
const _5f0d2c08 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesdelivery.vue' /* webpackChunkName: "pages/app/salesdelivery" */))
const _174e2368 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesdelivery\\form.vue' /* webpackChunkName: "pages/app/salesdelivery/form" */))
const _6afc5da0 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesdp.vue' /* webpackChunkName: "pages/app/salesdp" */))
const _e9dae438 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesdp\\form.vue' /* webpackChunkName: "pages/app/salesdp/form" */))
const _39aa4049 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesinvoice.vue' /* webpackChunkName: "pages/app/salesinvoice" */))
const _104c0dcd = () => interopDefault(import('..\\nuxt\\pages\\app\\salesinvoice\\form.vue' /* webpackChunkName: "pages/app/salesinvoice/form" */))
const _4812fcea = () => interopDefault(import('..\\nuxt\\pages\\app\\salesorder.vue' /* webpackChunkName: "pages/app/salesorder" */))
const _09030dae = () => interopDefault(import('..\\nuxt\\pages\\app\\salesorder\\form.vue' /* webpackChunkName: "pages/app/salesorder/form" */))
const _113b1aa2 = () => interopDefault(import('..\\nuxt\\pages\\app\\salespayment.vue' /* webpackChunkName: "pages/app/salespayment" */))
const _6e5c0166 = () => interopDefault(import('..\\nuxt\\pages\\app\\salespayment\\form.vue' /* webpackChunkName: "pages/app/salespayment/form" */))
const _07056d18 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesquote.vue' /* webpackChunkName: "pages/app/salesquote" */))
const _f47fb948 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesquote\\form.vue' /* webpackChunkName: "pages/app/salesquote/form" */))
const _40ea0378 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesreturn.vue' /* webpackChunkName: "pages/app/salesreturn" */))
const _b4b1b8f0 = () => interopDefault(import('..\\nuxt\\pages\\app\\salesreturn\\form.vue' /* webpackChunkName: "pages/app/salesreturn/form" */))
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
    path: "/app/account",
    component: _2ef3d2b5,
    name: "app-account"
  }, {
    path: "/app/account-category",
    component: _1a9795c6,
    name: "app-account-category"
  }, {
    path: "/app/bank-deposit",
    component: _bac68c96,
    name: "app-bank-deposit"
  }, {
    path: "/app/bank-transfer",
    component: _2271fd64,
    name: "app-bank-transfer"
  }, {
    path: "/app/bank-withdraw",
    component: _0361a903,
    name: "app-bank-withdraw"
  }, {
    path: "/app/budgeting",
    component: _037ba9f6,
    name: "app-budgeting"
  }, {
    path: "/app/closing",
    component: _068a213d,
    name: "app-closing"
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
    path: "/app/journal-entry",
    component: _3df4cb64,
    name: "app-journal-entry"
  }, {
    path: "/app/price-list",
    component: _53de0dca,
    name: "app-price-list"
  }, {
    path: "/app/purchasecreditmemo",
    component: _166f519c,
    name: "app-purchasecreditmemo",
    children: [{
      path: "form",
      component: _1434d640,
      name: "app-purchasecreditmemo-form"
    }]
  }, {
    path: "/app/purchasedp",
    component: _49270ef5,
    name: "app-purchasedp",
    children: [{
      path: "form",
      component: _61262b79,
      name: "app-purchasedp-form"
    }]
  }, {
    path: "/app/purchaseinvoice",
    component: _40921c14,
    name: "app-purchaseinvoice",
    children: [{
      path: "form",
      component: _1596b550,
      name: "app-purchaseinvoice-form"
    }]
  }, {
    path: "/app/purchaseorder",
    component: _12454f75,
    name: "app-purchaseorder",
    children: [{
      path: "form",
      component: _5db20bf9,
      name: "app-purchaseorder-form"
    }]
  }, {
    path: "/app/purchasepayment",
    component: _1822f66d,
    name: "app-purchasepayment",
    children: [{
      path: "form",
      component: _534498f1,
      name: "app-purchasepayment-form"
    }]
  }, {
    path: "/app/purchasequote",
    component: _5d9080ba,
    name: "app-purchasequote",
    children: [{
      path: "form",
      component: _4b21bcb2,
      name: "app-purchasequote-form"
    }]
  }, {
    path: "/app/purchasereceipt",
    component: _25ac64c2,
    name: "app-purchasereceipt",
    children: [{
      path: "form",
      component: _7c85c2a3,
      name: "app-purchasereceipt-form"
    }]
  }, {
    path: "/app/purchasereturn",
    component: _5ba2fd19,
    name: "app-purchasereturn",
    children: [{
      path: "form",
      component: _325022c6,
      name: "app-purchasereturn-form"
    }]
  }, {
    path: "/app/reconcile",
    component: _74a5c154,
    name: "app-reconcile"
  }, {
    path: "/app/reporting-period",
    component: _b324f330,
    name: "app-reporting-period"
  }, {
    path: "/app/salescreditmemo",
    component: _76ccc547,
    name: "app-salescreditmemo",
    children: [{
      path: "form",
      component: _c22b476a,
      name: "app-salescreditmemo-form"
    }]
  }, {
    path: "/app/salesdelivery",
    component: _5f0d2c08,
    name: "app-salesdelivery",
    children: [{
      path: "form",
      component: _174e2368,
      name: "app-salesdelivery-form"
    }]
  }, {
    path: "/app/salesdp",
    component: _6afc5da0,
    name: "app-salesdp",
    children: [{
      path: "form",
      component: _e9dae438,
      name: "app-salesdp-form"
    }]
  }, {
    path: "/app/salesinvoice",
    component: _39aa4049,
    name: "app-salesinvoice",
    children: [{
      path: "form",
      component: _104c0dcd,
      name: "app-salesinvoice-form"
    }]
  }, {
    path: "/app/salesorder",
    component: _4812fcea,
    name: "app-salesorder",
    children: [{
      path: "form",
      component: _09030dae,
      name: "app-salesorder-form"
    }]
  }, {
    path: "/app/salespayment",
    component: _113b1aa2,
    name: "app-salespayment",
    children: [{
      path: "form",
      component: _6e5c0166,
      name: "app-salespayment-form"
    }]
  }, {
    path: "/app/salesquote",
    component: _07056d18,
    name: "app-salesquote",
    children: [{
      path: "form",
      component: _f47fb948,
      name: "app-salesquote-form"
    }]
  }, {
    path: "/app/salesreturn",
    component: _40ea0378,
    name: "app-salesreturn",
    children: [{
      path: "form",
      component: _b4b1b8f0,
      name: "app-salesreturn-form"
    }]
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
    path: "/",
    component: _2c1039ce,
    name: "index"
  }, {
    path: "/__nuxt_laravel",
    component: _2c1039ce,
    name: "__nuxt_laravel"
  }, {
    path: "/__nuxt_laravel",
    component: _2c1039ce,
    name: "__nuxt_laravel"
  }, {
    path: "/__nuxt_laravel",
    component: _2c1039ce,
    name: "__nuxt_laravel"
  }, {
    path: "/__nuxt_laravel",
    component: _2c1039ce,
    name: "__nuxt_laravel"
  }, {
    path: "/__nuxt_laravel",
    component: _2c1039ce,
    name: "__nuxt_laravel"
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
