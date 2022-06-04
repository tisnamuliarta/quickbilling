import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _ddedff0c = () => interopDefault(import('../nuxt/pages/list/index.vue' /* webpackChunkName: "pages/list/index" */))
const _e375dbc8 = () => interopDefault(import('../nuxt/pages/app/accounting.vue' /* webpackChunkName: "pages/app/accounting" */))
const _1455538c = () => interopDefault(import('../nuxt/pages/app/accounting/account.vue' /* webpackChunkName: "pages/app/accounting/account" */))
const _5a3bd59b = () => interopDefault(import('../nuxt/pages/app/accounting/reconcile.vue' /* webpackChunkName: "pages/app/accounting/reconcile" */))
const _f64bb4ae = () => interopDefault(import('../nuxt/pages/app/contact.vue' /* webpackChunkName: "pages/app/contact" */))
const _5cf5b534 = () => interopDefault(import('../nuxt/pages/app/contact/customer.vue' /* webpackChunkName: "pages/app/contact/customer" */))
const _382fbc3a = () => interopDefault(import('../nuxt/pages/app/contact/customer-detail.vue' /* webpackChunkName: "pages/app/contact/customer-detail" */))
const _937125c4 = () => interopDefault(import('../nuxt/pages/app/contact/vendor.vue' /* webpackChunkName: "pages/app/contact/vendor" */))
const _46b5ade0 = () => interopDefault(import('../nuxt/pages/app/contact/vendor-detail.vue' /* webpackChunkName: "pages/app/contact/vendor-detail" */))
const _44584a4c = () => interopDefault(import('../nuxt/pages/app/item.vue' /* webpackChunkName: "pages/app/item" */))
const _6b8ff663 = () => interopDefault(import('../nuxt/pages/app/item/list.vue' /* webpackChunkName: "pages/app/item/list" */))
const _4839d6b2 = () => interopDefault(import('../nuxt/pages/app/item/price-list.vue' /* webpackChunkName: "pages/app/item/price-list" */))
const _74d1611a = () => interopDefault(import('../nuxt/pages/app/item/form/fitem.vue' /* webpackChunkName: "pages/app/item/form/fitem" */))
const _dcd134fc = () => interopDefault(import('../nuxt/pages/app/item/form/fprice-list.vue' /* webpackChunkName: "pages/app/item/form/fprice-list" */))
const _3b23942e = () => interopDefault(import('../nuxt/pages/app/payroll.vue' /* webpackChunkName: "pages/app/payroll" */))
const _31877926 = () => interopDefault(import('../nuxt/pages/app/payroll/contractor.vue' /* webpackChunkName: "pages/app/payroll/contractor" */))
const _f63d69c2 = () => interopDefault(import('../nuxt/pages/app/payroll/employee.vue' /* webpackChunkName: "pages/app/payroll/employee" */))
const _dbde172c = () => interopDefault(import('../nuxt/pages/app/payroll/overview.vue' /* webpackChunkName: "pages/app/payroll/overview" */))
const _4a652822 = () => interopDefault(import('../nuxt/pages/app/project.vue' /* webpackChunkName: "pages/app/project" */))
const _5ef3ce48 = () => interopDefault(import('../nuxt/pages/app/purchase.vue' /* webpackChunkName: "pages/app/purchase" */))
const _e26d4134 = () => interopDefault(import('../nuxt/pages/app/purchase/invoice.vue' /* webpackChunkName: "pages/app/purchase/invoice" */))
const _2b24f7b5 = () => interopDefault(import('../nuxt/pages/app/purchase/list.vue' /* webpackChunkName: "pages/app/purchase/list" */))
const _2b577790 = () => interopDefault(import('../nuxt/pages/app/purchase/overview.vue' /* webpackChunkName: "pages/app/purchase/overview" */))
const _74c36bc8 = () => interopDefault(import('../nuxt/pages/app/reports.vue' /* webpackChunkName: "pages/app/reports" */))
const _21fbca35 = () => interopDefault(import('../nuxt/pages/app/reports/list.vue' /* webpackChunkName: "pages/app/reports/list" */))
const _d1a3502e = () => interopDefault(import('../nuxt/pages/app/reports/performance.vue' /* webpackChunkName: "pages/app/reports/performance" */))
const _59edfcd5 = () => interopDefault(import('../nuxt/pages/app/sales.vue' /* webpackChunkName: "pages/app/sales" */))
const _35329e1a = () => interopDefault(import('../nuxt/pages/app/sales/invoice.vue' /* webpackChunkName: "pages/app/sales/invoice" */))
const _6bf2d948 = () => interopDefault(import('../nuxt/pages/app/sales/list.vue' /* webpackChunkName: "pages/app/sales/list" */))
const _af3750ba = () => interopDefault(import('../nuxt/pages/app/sales/overview.vue' /* webpackChunkName: "pages/app/sales/overview" */))
const _7f320c4c = () => interopDefault(import('../nuxt/pages/app/task.vue' /* webpackChunkName: "pages/app/task" */))
const _3d72e7ca = () => interopDefault(import('../nuxt/pages/auth/login.vue' /* webpackChunkName: "pages/auth/login" */))
const _eb812ed8 = () => interopDefault(import('../nuxt/pages/documents/form.vue' /* webpackChunkName: "pages/documents/form" */))
const _838ee124 = () => interopDefault(import('../nuxt/pages/documents/list.vue' /* webpackChunkName: "pages/documents/list" */))
const _956a8416 = () => interopDefault(import('../nuxt/pages/documents/view.vue' /* webpackChunkName: "pages/documents/view" */))
const _49c4a7e2 = () => interopDefault(import('../nuxt/pages/home/business-overview.vue' /* webpackChunkName: "pages/home/business-overview" */))
const _54dd437e = () => interopDefault(import('../nuxt/pages/home/getthingdone.vue' /* webpackChunkName: "pages/home/getthingdone" */))
const _bb0077b4 = () => interopDefault(import('../nuxt/pages/list/account-category.vue' /* webpackChunkName: "pages/list/account-category" */))
const _310ef009 = () => interopDefault(import('../nuxt/pages/list/accountmapping.vue' /* webpackChunkName: "pages/list/accountmapping" */))
const _4b19a3aa = () => interopDefault(import('../nuxt/pages/list/attachment.vue' /* webpackChunkName: "pages/list/attachment" */))
const _2f2768a5 = () => interopDefault(import('../nuxt/pages/list/budgeting.vue' /* webpackChunkName: "pages/list/budgeting" */))
const _6ce246dd = () => interopDefault(import('../nuxt/pages/list/closing.vue' /* webpackChunkName: "pages/list/closing" */))
const _435e02a5 = () => interopDefault(import('../nuxt/pages/list/company.vue' /* webpackChunkName: "pages/list/company" */))
const _11b0da19 = () => interopDefault(import('../nuxt/pages/list/currency.vue' /* webpackChunkName: "pages/list/currency" */))
const _4e7b8df8 = () => interopDefault(import('../nuxt/pages/list/journal-entry.vue' /* webpackChunkName: "pages/list/journal-entry" */))
const _13ba2067 = () => interopDefault(import('../nuxt/pages/list/menu.vue' /* webpackChunkName: "pages/list/menu" */))
const _0dfddb8f = () => interopDefault(import('../nuxt/pages/list/paymentmethod.vue' /* webpackChunkName: "pages/list/paymentmethod" */))
const _24a2bf1a = () => interopDefault(import('../nuxt/pages/list/paymentterm.vue' /* webpackChunkName: "pages/list/paymentterm" */))
const _3f14d72c = () => interopDefault(import('../nuxt/pages/list/permissions.vue' /* webpackChunkName: "pages/list/permissions" */))
const _3fa0842a = () => interopDefault(import('../nuxt/pages/list/price-list.vue' /* webpackChunkName: "pages/list/price-list" */))
const _1b144105 = () => interopDefault(import('../nuxt/pages/list/recurring.vue' /* webpackChunkName: "pages/list/recurring" */))
const _2e55b4c8 = () => interopDefault(import('../nuxt/pages/list/reporting-period.vue' /* webpackChunkName: "pages/list/reporting-period" */))
const _53850e36 = () => interopDefault(import('../nuxt/pages/list/roles.vue' /* webpackChunkName: "pages/list/roles" */))
const _1e0b44a5 = () => interopDefault(import('../nuxt/pages/list/setup.vue' /* webpackChunkName: "pages/list/setup" */))
const _6446c6d3 = () => interopDefault(import('../nuxt/pages/list/tax.vue' /* webpackChunkName: "pages/list/tax" */))
const _531a4aa0 = () => interopDefault(import('../nuxt/pages/list/users.vue' /* webpackChunkName: "pages/list/users" */))
const _114349b6 = () => interopDefault(import('../nuxt/pages/reports/bank.vue' /* webpackChunkName: "pages/reports/bank" */))
const _7ef8e877 = () => interopDefault(import('../nuxt/pages/reports/bp.vue' /* webpackChunkName: "pages/reports/bp" */))
const _a744e708 = () => interopDefault(import('../nuxt/pages/reports/item.vue' /* webpackChunkName: "pages/reports/item" */))
const _0b4658c4 = () => interopDefault(import('../nuxt/pages/tools/audit.vue' /* webpackChunkName: "pages/tools/audit" */))
const _091791fe = () => interopDefault(import('../nuxt/pages/tools/export.vue' /* webpackChunkName: "pages/tools/export" */))
const _6da93672 = () => interopDefault(import('../nuxt/pages/tools/import.vue' /* webpackChunkName: "pages/tools/import" */))
const _52cc8292 = () => interopDefault(import('../nuxt/pages/app/bank/deposit.vue' /* webpackChunkName: "pages/app/bank/deposit" */))
const _1b7e9842 = () => interopDefault(import('../nuxt/pages/app/bank/reconcile.vue' /* webpackChunkName: "pages/app/bank/reconcile" */))
const _783b5027 = () => interopDefault(import('../nuxt/pages/app/bank/transfer.vue' /* webpackChunkName: "pages/app/bank/transfer" */))
const _592afbc6 = () => interopDefault(import('../nuxt/pages/app/bank/withdraw.vue' /* webpackChunkName: "pages/app/bank/withdraw" */))
const _9a19c732 = () => interopDefault(import('../nuxt/pages/app/form/accounting/account.vue' /* webpackChunkName: "pages/app/form/accounting/account" */))
const _4700c488 = () => interopDefault(import('../nuxt/pages/app/form/accounting/reconcile.vue' /* webpackChunkName: "pages/app/form/accounting/reconcile" */))
const _1d3922a1 = () => interopDefault(import('../nuxt/pages/app/form/contact/customer.vue' /* webpackChunkName: "pages/app/form/contact/customer" */))
const _cd1d5c6a = () => interopDefault(import('../nuxt/pages/app/form/contact/vendor.vue' /* webpackChunkName: "pages/app/form/contact/vendor" */))
const _4fe5a18b = () => interopDefault(import('../nuxt/pages/app/form/item/item.vue' /* webpackChunkName: "pages/app/form/item/item" */))
const _5df5c59a = () => interopDefault(import('../nuxt/pages/app/form/item/price-list.vue' /* webpackChunkName: "pages/app/form/item/price-list" */))
const _22b5815a = () => interopDefault(import('../nuxt/pages/app/form/payroll/contractor.vue' /* webpackChunkName: "pages/app/form/payroll/contractor" */))
const _4524b88c = () => interopDefault(import('../nuxt/pages/app/form/payroll/employee.vue' /* webpackChunkName: "pages/app/form/payroll/employee" */))
const _0e0d747d = () => interopDefault(import('../nuxt/pages/app/form/purchase/creditmemo.vue' /* webpackChunkName: "pages/app/form/purchase/creditmemo" */))
const _14079e84 = () => interopDefault(import('../nuxt/pages/app/form/purchase/delivery.vue' /* webpackChunkName: "pages/app/form/purchase/delivery" */))
const _e03aba54 = () => interopDefault(import('../nuxt/pages/app/form/purchase/dp.vue' /* webpackChunkName: "pages/app/form/purchase/dp" */))
const _4f0cccd3 = () => interopDefault(import('../nuxt/pages/app/form/purchase/invoice.vue' /* webpackChunkName: "pages/app/form/purchase/invoice" */))
const _50e323f4 = () => interopDefault(import('../nuxt/pages/app/form/purchase/order.vue' /* webpackChunkName: "pages/app/form/purchase/order" */))
const _269da72c = () => interopDefault(import('../nuxt/pages/app/form/purchase/payment.vue' /* webpackChunkName: "pages/app/form/purchase/payment" */))
const _0fd59422 = () => interopDefault(import('../nuxt/pages/app/form/purchase/quote.vue' /* webpackChunkName: "pages/app/form/purchase/quote" */))
const _1e808f0c = () => interopDefault(import('../nuxt/pages/app/form/purchase/return.vue' /* webpackChunkName: "pages/app/form/purchase/return" */))
const _550b09ca = () => interopDefault(import('../nuxt/pages/app/form/sales/creditmemo.vue' /* webpackChunkName: "pages/app/form/sales/creditmemo" */))
const _0e08b54b = () => interopDefault(import('../nuxt/pages/app/form/sales/delivery.vue' /* webpackChunkName: "pages/app/form/sales/delivery" */))
const _3a9cd523 = () => interopDefault(import('../nuxt/pages/app/form/sales/dp.vue' /* webpackChunkName: "pages/app/form/sales/dp" */))
const _1e4702a6 = () => interopDefault(import('../nuxt/pages/app/form/sales/invoice.vue' /* webpackChunkName: "pages/app/form/sales/invoice" */))
const _0ac6b287 = () => interopDefault(import('../nuxt/pages/app/form/sales/order.vue' /* webpackChunkName: "pages/app/form/sales/order" */))
const _14504602 = () => interopDefault(import('../nuxt/pages/app/form/sales/payment.vue' /* webpackChunkName: "pages/app/form/sales/payment" */))
const _6c8dba96 = () => interopDefault(import('../nuxt/pages/app/form/sales/quote.vue' /* webpackChunkName: "pages/app/form/sales/quote" */))
const _734dfc47 = () => interopDefault(import('../nuxt/pages/app/form/sales/return.vue' /* webpackChunkName: "pages/app/form/sales/return" */))
const _ce5930ee = () => interopDefault(import('../nuxt/pages/index.vue' /* webpackChunkName: "pages/index" */))

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
    component: _ddedff0c,
    name: "list"
  }, {
    path: "/app/accounting",
    component: _e375dbc8,
    name: "app-accounting",
    children: [{
      path: "account",
      component: _1455538c,
      name: "app-accounting-account"
    }, {
      path: "reconcile",
      component: _5a3bd59b,
      name: "app-accounting-reconcile"
    }]
  }, {
    path: "/app/contact",
    component: _f64bb4ae,
    name: "app-contact",
    children: [{
      path: "customer",
      component: _5cf5b534,
      name: "app-contact-customer"
    }, {
      path: "customer-detail",
      component: _382fbc3a,
      name: "app-contact-customer-detail"
    }, {
      path: "vendor",
      component: _937125c4,
      name: "app-contact-vendor"
    }, {
      path: "vendor-detail",
      component: _46b5ade0,
      name: "app-contact-vendor-detail"
    }]
  }, {
    path: "/app/item",
    component: _44584a4c,
    name: "app-item",
    children: [{
      path: "list",
      component: _6b8ff663,
      name: "app-item-list"
    }, {
      path: "price-list",
      component: _4839d6b2,
      name: "app-item-price-list"
    }, {
      path: "form/fitem",
      component: _74d1611a,
      name: "app-item-form-fitem"
    }, {
      path: "form/fprice-list",
      component: _dcd134fc,
      name: "app-item-form-fprice-list"
    }]
  }, {
    path: "/app/payroll",
    component: _3b23942e,
    name: "app-payroll",
    children: [{
      path: "contractor",
      component: _31877926,
      name: "app-payroll-contractor"
    }, {
      path: "employee",
      component: _f63d69c2,
      name: "app-payroll-employee"
    }, {
      path: "overview",
      component: _dbde172c,
      name: "app-payroll-overview"
    }]
  }, {
    path: "/app/project",
    component: _4a652822,
    name: "app-project"
  }, {
    path: "/app/purchase",
    component: _5ef3ce48,
    name: "app-purchase",
    children: [{
      path: "invoice",
      component: _e26d4134,
      name: "app-purchase-invoice"
    }, {
      path: "list",
      component: _2b24f7b5,
      name: "app-purchase-list"
    }, {
      path: "overview",
      component: _2b577790,
      name: "app-purchase-overview"
    }]
  }, {
    path: "/app/reports",
    component: _74c36bc8,
    name: "app-reports",
    children: [{
      path: "list",
      component: _21fbca35,
      name: "app-reports-list"
    }, {
      path: "performance",
      component: _d1a3502e,
      name: "app-reports-performance"
    }]
  }, {
    path: "/app/sales",
    component: _59edfcd5,
    name: "app-sales",
    children: [{
      path: "invoice",
      component: _35329e1a,
      name: "app-sales-invoice"
    }, {
      path: "list",
      component: _6bf2d948,
      name: "app-sales-list"
    }, {
      path: "overview",
      component: _af3750ba,
      name: "app-sales-overview"
    }]
  }, {
    path: "/app/task",
    component: _7f320c4c,
    name: "app-task"
  }, {
    path: "/auth/login",
    component: _3d72e7ca,
    name: "auth-login"
  }, {
    path: "/documents/form",
    component: _eb812ed8,
    name: "documents-form"
  }, {
    path: "/documents/list",
    component: _838ee124,
    name: "documents-list"
  }, {
    path: "/documents/view",
    component: _956a8416,
    name: "documents-view"
  }, {
    path: "/home/business-overview",
    component: _49c4a7e2,
    name: "home-business-overview"
  }, {
    path: "/home/getthingdone",
    component: _54dd437e,
    name: "home-getthingdone"
  }, {
    path: "/list/account-category",
    component: _bb0077b4,
    name: "list-account-category"
  }, {
    path: "/list/accountmapping",
    component: _310ef009,
    name: "list-accountmapping"
  }, {
    path: "/list/attachment",
    component: _4b19a3aa,
    name: "list-attachment"
  }, {
    path: "/list/budgeting",
    component: _2f2768a5,
    name: "list-budgeting"
  }, {
    path: "/list/closing",
    component: _6ce246dd,
    name: "list-closing"
  }, {
    path: "/list/company",
    component: _435e02a5,
    name: "list-company"
  }, {
    path: "/list/currency",
    component: _11b0da19,
    name: "list-currency"
  }, {
    path: "/list/journal-entry",
    component: _4e7b8df8,
    name: "list-journal-entry"
  }, {
    path: "/list/menu",
    component: _13ba2067,
    name: "list-menu"
  }, {
    path: "/list/paymentmethod",
    component: _0dfddb8f,
    name: "list-paymentmethod"
  }, {
    path: "/list/paymentterm",
    component: _24a2bf1a,
    name: "list-paymentterm"
  }, {
    path: "/list/permissions",
    component: _3f14d72c,
    name: "list-permissions"
  }, {
    path: "/list/price-list",
    component: _3fa0842a,
    name: "list-price-list"
  }, {
    path: "/list/recurring",
    component: _1b144105,
    name: "list-recurring"
  }, {
    path: "/list/reporting-period",
    component: _2e55b4c8,
    name: "list-reporting-period"
  }, {
    path: "/list/roles",
    component: _53850e36,
    name: "list-roles"
  }, {
    path: "/list/setup",
    component: _1e0b44a5,
    name: "list-setup"
  }, {
    path: "/list/tax",
    component: _6446c6d3,
    name: "list-tax"
  }, {
    path: "/list/users",
    component: _531a4aa0,
    name: "list-users"
  }, {
    path: "/reports/bank",
    component: _114349b6,
    name: "reports-bank"
  }, {
    path: "/reports/bp",
    component: _7ef8e877,
    name: "reports-bp"
  }, {
    path: "/reports/item",
    component: _a744e708,
    name: "reports-item"
  }, {
    path: "/tools/audit",
    component: _0b4658c4,
    name: "tools-audit"
  }, {
    path: "/tools/export",
    component: _091791fe,
    name: "tools-export"
  }, {
    path: "/tools/import",
    component: _6da93672,
    name: "tools-import"
  }, {
    path: "/app/bank/deposit",
    component: _52cc8292,
    name: "app-bank-deposit"
  }, {
    path: "/app/bank/reconcile",
    component: _1b7e9842,
    name: "app-bank-reconcile"
  }, {
    path: "/app/bank/transfer",
    component: _783b5027,
    name: "app-bank-transfer"
  }, {
    path: "/app/bank/withdraw",
    component: _592afbc6,
    name: "app-bank-withdraw"
  }, {
    path: "/app/form/accounting/account",
    component: _9a19c732,
    name: "app-form-accounting-account"
  }, {
    path: "/app/form/accounting/reconcile",
    component: _4700c488,
    name: "app-form-accounting-reconcile"
  }, {
    path: "/app/form/contact/customer",
    component: _1d3922a1,
    name: "app-form-contact-customer"
  }, {
    path: "/app/form/contact/vendor",
    component: _cd1d5c6a,
    name: "app-form-contact-vendor"
  }, {
    path: "/app/form/item/item",
    component: _4fe5a18b,
    name: "app-form-item-item"
  }, {
    path: "/app/form/item/price-list",
    component: _5df5c59a,
    name: "app-form-item-price-list"
  }, {
    path: "/app/form/payroll/contractor",
    component: _22b5815a,
    name: "app-form-payroll-contractor"
  }, {
    path: "/app/form/payroll/employee",
    component: _4524b88c,
    name: "app-form-payroll-employee"
  }, {
    path: "/app/form/purchase/creditmemo",
    component: _0e0d747d,
    name: "app-form-purchase-creditmemo"
  }, {
    path: "/app/form/purchase/delivery",
    component: _14079e84,
    name: "app-form-purchase-delivery"
  }, {
    path: "/app/form/purchase/dp",
    component: _e03aba54,
    name: "app-form-purchase-dp"
  }, {
    path: "/app/form/purchase/invoice",
    component: _4f0cccd3,
    name: "app-form-purchase-invoice"
  }, {
    path: "/app/form/purchase/order",
    component: _50e323f4,
    name: "app-form-purchase-order"
  }, {
    path: "/app/form/purchase/payment",
    component: _269da72c,
    name: "app-form-purchase-payment"
  }, {
    path: "/app/form/purchase/quote",
    component: _0fd59422,
    name: "app-form-purchase-quote"
  }, {
    path: "/app/form/purchase/return",
    component: _1e808f0c,
    name: "app-form-purchase-return"
  }, {
    path: "/app/form/sales/creditmemo",
    component: _550b09ca,
    name: "app-form-sales-creditmemo"
  }, {
    path: "/app/form/sales/delivery",
    component: _0e08b54b,
    name: "app-form-sales-delivery"
  }, {
    path: "/app/form/sales/dp",
    component: _3a9cd523,
    name: "app-form-sales-dp"
  }, {
    path: "/app/form/sales/invoice",
    component: _1e4702a6,
    name: "app-form-sales-invoice"
  }, {
    path: "/app/form/sales/order",
    component: _0ac6b287,
    name: "app-form-sales-order"
  }, {
    path: "/app/form/sales/payment",
    component: _14504602,
    name: "app-form-sales-payment"
  }, {
    path: "/app/form/sales/quote",
    component: _6c8dba96,
    name: "app-form-sales-quote"
  }, {
    path: "/app/form/sales/return",
    component: _734dfc47,
    name: "app-form-sales-return"
  }, {
    path: "/",
    component: _ce5930ee,
    name: "index"
  }, {
    path: "/__nuxt_laravel",
    component: _ce5930ee,
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
