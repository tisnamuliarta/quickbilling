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
const _796384be = () => interopDefault(import('..\\nuxt\\pages\\app\\project.vue' /* webpackChunkName: "pages/app/project" */))
const _032d68a9 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase.vue' /* webpackChunkName: "pages/app/purchase" */))
const _671c2e58 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\invoice.vue' /* webpackChunkName: "pages/app/purchase/invoice" */))
const _6f209507 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\list.vue' /* webpackChunkName: "pages/app/purchase/list" */))
const _ad9d6796 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\order.vue' /* webpackChunkName: "pages/app/purchase/order" */))
const _ba7fc83c = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\overview.vue' /* webpackChunkName: "pages/app/purchase/overview" */))
const _02ed2c60 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\form\\creditmemo.vue' /* webpackChunkName: "pages/app/purchase/form/creditmemo" */))
const _e204248e = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\form\\dp.vue' /* webpackChunkName: "pages/app/purchase/form/dp" */))
const _52cbd7d0 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\form\\invoice.vue' /* webpackChunkName: "pages/app/purchase/form/invoice" */))
const _59f2ca31 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\form\\order.vue' /* webpackChunkName: "pages/app/purchase/form/order" */))
const _2a5cb229 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\form\\payment.vue' /* webpackChunkName: "pages/app/purchase/form/payment" */))
const _18e53a5f = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\form\\quote.vue' /* webpackChunkName: "pages/app/purchase/form/quote" */))
const _0138ed4a = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\form\\receipt.vue' /* webpackChunkName: "pages/app/purchase/form/receipt" */))
const _09a4d9dd = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\form\\return.vue' /* webpackChunkName: "pages/app/purchase/form/return" */))
const _10cc6614 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales.vue' /* webpackChunkName: "pages/app/sales" */))
const _3fdc586e = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\invoice.vue' /* webpackChunkName: "pages/app/sales/invoice" */))
const _19eed39c = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\list.vue' /* webpackChunkName: "pages/app/sales/list" */))
const _bb6d1f2c = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\order.vue' /* webpackChunkName: "pages/app/sales/order" */))
const _031d8f8d = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\overview.vue' /* webpackChunkName: "pages/app/sales/overview" */))
const _7dddc50b = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\form\\creditmemo.vue' /* webpackChunkName: "pages/app/sales/form/creditmemo" */))
const _13234ccc = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\form\\delivery.vue' /* webpackChunkName: "pages/app/sales/form/delivery" */))
const _21493d38 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\form\\dp.vue' /* webpackChunkName: "pages/app/sales/form/dp" */))
const _05aaf705 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\form\\invoice.vue' /* webpackChunkName: "pages/app/sales/form/invoice" */))
const _750a32a6 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\form\\order.vue' /* webpackChunkName: "pages/app/sales/form/order" */))
const _45885d44 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\form\\payment.vue' /* webpackChunkName: "pages/app/sales/form/payment" */))
const _33fca2d4 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\form\\quote.vue' /* webpackChunkName: "pages/app/sales/form/quote" */))
const _517a8008 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\form\\return.vue' /* webpackChunkName: "pages/app/sales/form/return" */))
const _6c51ff2d = () => interopDefault(import('..\\nuxt\\pages\\app\\task.vue' /* webpackChunkName: "pages/app/task" */))
const _eedd1670 = () => interopDefault(import('..\\nuxt\\pages\\auth\\login.vue' /* webpackChunkName: "pages/auth/login" */))
const _fe50f0fa = () => interopDefault(import('..\\nuxt\\pages\\documents\\form.vue' /* webpackChunkName: "pages/documents/form" */))
const _965ea346 = () => interopDefault(import('..\\nuxt\\pages\\documents\\list.vue' /* webpackChunkName: "pages/documents/list" */))
const _a83a4638 = () => interopDefault(import('..\\nuxt\\pages\\documents\\view.vue' /* webpackChunkName: "pages/documents/view" */))
const _d91e1fa4 = () => interopDefault(import('..\\nuxt\\pages\\home\\business-overview.vue' /* webpackChunkName: "pages/home/business-overview" */))
const _317dc2c2 = () => interopDefault(import('..\\nuxt\\pages\\home\\getthingdone.vue' /* webpackChunkName: "pages/home/getthingdone" */))
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
    path: "/app/project",
    component: _796384be,
    name: "app-project"
  }, {
    path: "/app/purchase",
    component: _032d68a9,
    name: "app-purchase",
    children: [{
      path: "invoice",
      component: _671c2e58,
      name: "app-purchase-invoice"
    }, {
      path: "list",
      component: _6f209507,
      name: "app-purchase-list"
    }, {
      path: "order",
      component: _ad9d6796,
      name: "app-purchase-order"
    }, {
      path: "overview",
      component: _ba7fc83c,
      name: "app-purchase-overview"
    }, {
      path: "form/creditmemo",
      component: _02ed2c60,
      name: "app-purchase-form-creditmemo"
    }, {
      path: "form/dp",
      component: _e204248e,
      name: "app-purchase-form-dp"
    }, {
      path: "form/invoice",
      component: _52cbd7d0,
      name: "app-purchase-form-invoice"
    }, {
      path: "form/order",
      component: _59f2ca31,
      name: "app-purchase-form-order"
    }, {
      path: "form/payment",
      component: _2a5cb229,
      name: "app-purchase-form-payment"
    }, {
      path: "form/quote",
      component: _18e53a5f,
      name: "app-purchase-form-quote"
    }, {
      path: "form/receipt",
      component: _0138ed4a,
      name: "app-purchase-form-receipt"
    }, {
      path: "form/return",
      component: _09a4d9dd,
      name: "app-purchase-form-return"
    }]
  }, {
    path: "/app/sales",
    component: _10cc6614,
    name: "app-sales",
    children: [{
      path: "invoice",
      component: _3fdc586e,
      name: "app-sales-invoice"
    }, {
      path: "list",
      component: _19eed39c,
      name: "app-sales-list"
    }, {
      path: "order",
      component: _bb6d1f2c,
      name: "app-sales-order"
    }, {
      path: "overview",
      component: _031d8f8d,
      name: "app-sales-overview"
    }, {
      path: "form/creditmemo",
      component: _7dddc50b,
      name: "app-sales-form-creditmemo"
    }, {
      path: "form/delivery",
      component: _13234ccc,
      name: "app-sales-form-delivery"
    }, {
      path: "form/dp",
      component: _21493d38,
      name: "app-sales-form-dp"
    }, {
      path: "form/invoice",
      component: _05aaf705,
      name: "app-sales-form-invoice"
    }, {
      path: "form/order",
      component: _750a32a6,
      name: "app-sales-form-order"
    }, {
      path: "form/payment",
      component: _45885d44,
      name: "app-sales-form-payment"
    }, {
      path: "form/quote",
      component: _33fca2d4,
      name: "app-sales-form-quote"
    }, {
      path: "form/return",
      component: _517a8008,
      name: "app-sales-form-return"
    }]
  }, {
    path: "/app/task",
    component: _6c51ff2d,
    name: "app-task"
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
    path: "/home/business-overview",
    component: _d91e1fa4,
    name: "home-business-overview"
  }, {
    path: "/home/getthingdone",
    component: _317dc2c2,
    name: "home-getthingdone"
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
