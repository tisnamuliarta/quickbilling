import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _0afc7872 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\index.vue' /* webpackChunkName: "pages/dashboard/index" */))
const _eedd1670 = () => interopDefault(import('..\\nuxt\\pages\\auth\\login.vue' /* webpackChunkName: "pages/auth/login" */))
const _77a1f29f = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tasks\\index.vue' /* webpackChunkName: "pages/dashboard/tasks/index" */))
const _53d52036 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\users\\index.vue' /* webpackChunkName: "pages/dashboard/users/index" */))
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
const _7b36837e = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\invoice.vue' /* webpackChunkName: "pages/dashboard/purchase/invoice" */))
const _9276ce3c = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\order.vue' /* webpackChunkName: "pages/dashboard/purchase/order" */))
const _cc14cecc = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\payment.vue' /* webpackChunkName: "pages/dashboard/purchase/payment" */))
const _75b70910 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\quote.vue' /* webpackChunkName: "pages/dashboard/purchase/quote" */))
const _6efc6fcc = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\receipt.vue' /* webpackChunkName: "pages/dashboard/purchase/receipt" */))
const _470ce14c = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\return.vue' /* webpackChunkName: "pages/dashboard/purchase/return" */))
const _ff2baca0 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\reports\\bank.vue' /* webpackChunkName: "pages/dashboard/reports/bank" */))
const _75a6447c = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\reports\\bp.vue' /* webpackChunkName: "pages/dashboard/reports/bp" */))
const _35695b07 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\reports\\item.vue' /* webpackChunkName: "pages/dashboard/reports/item" */))
const _d9a95596 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\delivery.vue' /* webpackChunkName: "pages/dashboard/sales/delivery" */))
const _70888508 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\invoice.vue' /* webpackChunkName: "pages/dashboard/sales/invoice" */))
const _2a8faa46 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\order.vue' /* webpackChunkName: "pages/dashboard/sales/order" */))
const _c166d056 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\payment.vue' /* webpackChunkName: "pages/dashboard/sales/payment" */))
const _acaac9ea = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\quote.vue' /* webpackChunkName: "pages/dashboard/sales/quote" */))
const _dce8e29e = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\return.vue' /* webpackChunkName: "pages/dashboard/sales/return" */))
const _1514ffaf = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\company.vue' /* webpackChunkName: "pages/dashboard/settings/company" */))
const _b1cd60c6 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\menu.vue' /* webpackChunkName: "pages/dashboard/settings/menu" */))
const _33923f36 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\permissions.vue' /* webpackChunkName: "pages/dashboard/settings/permissions" */))
const _a2779f22 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\roles.vue' /* webpackChunkName: "pages/dashboard/settings/roles" */))
const _12dc07a2 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\setup.vue' /* webpackChunkName: "pages/dashboard/settings/setup" */))
const _3ebac780 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tasks\\show.vue' /* webpackChunkName: "pages/dashboard/tasks/show" */))
const _044d68bb = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tools\\audit.vue' /* webpackChunkName: "pages/dashboard/tools/audit" */))
const _2f964584 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tools\\export.vue' /* webpackChunkName: "pages/dashboard/tools/export" */))
const _bc697616 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tools\\import.vue' /* webpackChunkName: "pages/dashboard/tools/import" */))
const _67f8e6da = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\transactions\\attachment.vue' /* webpackChunkName: "pages/dashboard/transactions/attachment" */))
const _e9c55ee4 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\transactions\\list.vue' /* webpackChunkName: "pages/dashboard/transactions/list" */))
const _5f6b14c6 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\transactions\\recurring.vue' /* webpackChunkName: "pages/dashboard/transactions/recurring" */))
const _8da03368 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\users\\current.vue' /* webpackChunkName: "pages/dashboard/users/current" */))
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
    path: "/auth/login",
    component: _eedd1670,
    name: "auth-login"
  }, {
    path: "/dashboard/tasks",
    component: _77a1f29f,
    name: "dashboard-tasks"
  }, {
    path: "/dashboard/users",
    component: _53d52036,
    name: "dashboard-users"
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
    path: "/dashboard/sales/delivery",
    component: _d9a95596,
    name: "dashboard-sales-delivery"
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
    path: "/dashboard/settings/company",
    component: _1514ffaf,
    name: "dashboard-settings-company"
  }, {
    path: "/dashboard/settings/menu",
    component: _b1cd60c6,
    name: "dashboard-settings-menu"
  }, {
    path: "/dashboard/settings/permissions",
    component: _33923f36,
    name: "dashboard-settings-permissions"
  }, {
    path: "/dashboard/settings/roles",
    component: _a2779f22,
    name: "dashboard-settings-roles"
  }, {
    path: "/dashboard/settings/setup",
    component: _12dc07a2,
    name: "dashboard-settings-setup"
  }, {
    path: "/dashboard/tasks/show",
    component: _3ebac780,
    name: "dashboard-tasks-show"
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
    path: "/dashboard/transactions/attachment",
    component: _67f8e6da,
    name: "dashboard-transactions-attachment"
  }, {
    path: "/dashboard/transactions/list",
    component: _e9c55ee4,
    name: "dashboard-transactions-list"
  }, {
    path: "/dashboard/transactions/recurring",
    component: _5f6b14c6,
    name: "dashboard-transactions-recurring"
  }, {
    path: "/dashboard/users/current",
    component: _8da03368,
    name: "dashboard-users-current"
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
