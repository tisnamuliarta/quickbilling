import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _39d10f55 = () => interopDefault(import('../nuxt/pages/list/index.vue' /* webpackChunkName: "pages/list/index" */))
const _64643b77 = () => interopDefault(import('../nuxt/pages/app/accounting.vue' /* webpackChunkName: "pages/app/accounting" */))
const _31e216d6 = () => interopDefault(import('../nuxt/pages/app/accounting/account.vue' /* webpackChunkName: "pages/app/accounting/account" */))
const _38f16d94 = () => interopDefault(import('../nuxt/pages/app/accounting/reconcile.vue' /* webpackChunkName: "pages/app/accounting/reconcile" */))
const _15d81ba4 = () => interopDefault(import('../nuxt/pages/app/contact.vue' /* webpackChunkName: "pages/app/contact" */))
const _e5ada362 = () => interopDefault(import('../nuxt/pages/app/contact/customer.vue' /* webpackChunkName: "pages/app/contact/customer" */))
const _5b91debf = () => interopDefault(import('../nuxt/pages/app/contact/customer-detail.vue' /* webpackChunkName: "pages/app/contact/customer-detail" */))
const _a8a71e0e = () => interopDefault(import('../nuxt/pages/app/contact/vendor.vue' /* webpackChunkName: "pages/app/contact/vendor" */))
const _7cc828d5 = () => interopDefault(import('../nuxt/pages/app/contact/vendor-detail.vue' /* webpackChunkName: "pages/app/contact/vendor-detail" */))
const _5f77e475 = () => interopDefault(import('../nuxt/pages/app/item.vue' /* webpackChunkName: "pages/app/item" */))
const _96ee94b0 = () => interopDefault(import('../nuxt/pages/app/item/list.vue' /* webpackChunkName: "pages/app/item/list" */))
const _d9c2e7a8 = () => interopDefault(import('../nuxt/pages/app/item/price-list.vue' /* webpackChunkName: "pages/app/item/price-list" */))
const _7cd2c6f8 = () => interopDefault(import('../nuxt/pages/app/item/form/fitem.vue' /* webpackChunkName: "pages/app/item/form/fitem" */))
const _31ba6547 = () => interopDefault(import('../nuxt/pages/app/item/form/fprice-list.vue' /* webpackChunkName: "pages/app/item/form/fprice-list" */))
const _a9453e9a = () => interopDefault(import('../nuxt/pages/app/payroll.vue' /* webpackChunkName: "pages/app/payroll" */))
const _22c11781 = () => interopDefault(import('../nuxt/pages/app/payroll/contractor.vue' /* webpackChunkName: "pages/app/payroll/contractor" */))
const _95d6778c = () => interopDefault(import('../nuxt/pages/app/payroll/employee.vue' /* webpackChunkName: "pages/app/payroll/employee" */))
const _7b7724f6 = () => interopDefault(import('../nuxt/pages/app/payroll/overview.vue' /* webpackChunkName: "pages/app/payroll/overview" */))
const _8ac216b2 = () => interopDefault(import('../nuxt/pages/app/project.vue' /* webpackChunkName: "pages/app/project" */))
const _1418db3a = () => interopDefault(import('../nuxt/pages/app/purchase.vue' /* webpackChunkName: "pages/app/purchase" */))
const _82064efe = () => interopDefault(import('../nuxt/pages/app/purchase/invoice.vue' /* webpackChunkName: "pages/app/purchase/invoice" */))
const _811ae70c = () => interopDefault(import('../nuxt/pages/app/purchase/list.vue' /* webpackChunkName: "pages/app/purchase/list" */))
const _019321d5 = () => interopDefault(import('../nuxt/pages/app/purchase/overview.vue' /* webpackChunkName: "pages/app/purchase/overview" */))
const _36058f66 = () => interopDefault(import('../nuxt/pages/app/reports.vue' /* webpackChunkName: "pages/app/reports" */))
const _2613ee60 = () => interopDefault(import('../nuxt/pages/app/reports/list.vue' /* webpackChunkName: "pages/app/reports/list" */))
const _65aef624 = () => interopDefault(import('../nuxt/pages/app/reports/performance.vue' /* webpackChunkName: "pages/app/reports/performance" */))
const _0ccb269a = () => interopDefault(import('../nuxt/pages/app/sales.vue' /* webpackChunkName: "pages/app/sales" */))
const _79b445b8 = () => interopDefault(import('../nuxt/pages/app/sales/invoice.vue' /* webpackChunkName: "pages/app/sales/invoice" */))
const _421202a3 = () => interopDefault(import('../nuxt/pages/app/sales/list.vue' /* webpackChunkName: "pages/app/sales/list" */))
const _c46d4904 = () => interopDefault(import('../nuxt/pages/app/sales/overview.vue' /* webpackChunkName: "pages/app/sales/overview" */))
const _00d615e7 = () => interopDefault(import('../nuxt/pages/app/task.vue' /* webpackChunkName: "pages/app/task" */))
const _ebe2ca14 = () => interopDefault(import('../nuxt/pages/auth/login.vue' /* webpackChunkName: "pages/auth/login" */))
const _605e91ef = () => interopDefault(import('../nuxt/pages/documents/form.vue' /* webpackChunkName: "pages/documents/form" */))
const _d7508e6e = () => interopDefault(import('../nuxt/pages/documents/list.vue' /* webpackChunkName: "pages/documents/list" */))
const _e92c3160 = () => interopDefault(import('../nuxt/pages/documents/view.vue' /* webpackChunkName: "pages/documents/view" */))
const _67516b2c = () => interopDefault(import('../nuxt/pages/home/business-overview.vue' /* webpackChunkName: "pages/home/business-overview" */))
const _69def306 = () => interopDefault(import('../nuxt/pages/home/getthingdone.vue' /* webpackChunkName: "pages/home/getthingdone" */))
const _78bb6e6b = () => interopDefault(import('../nuxt/pages/list/account-category.vue' /* webpackChunkName: "pages/list/account-category" */))
const _2f6b30e4 = () => interopDefault(import('../nuxt/pages/list/accountmapping.vue' /* webpackChunkName: "pages/list/accountmapping" */))
const _6f8d9fa0 = () => interopDefault(import('../nuxt/pages/list/attachment.vue' /* webpackChunkName: "pages/list/attachment" */))
const _05469200 = () => interopDefault(import('../nuxt/pages/list/budgeting.vue' /* webpackChunkName: "pages/list/budgeting" */))
const _03e20af8 = () => interopDefault(import('../nuxt/pages/list/closing.vue' /* webpackChunkName: "pages/list/closing" */))
const _4b447280 = () => interopDefault(import('../nuxt/pages/list/company.vue' /* webpackChunkName: "pages/list/company" */))
const _5aa9995e = () => interopDefault(import('../nuxt/pages/list/currency.vue' /* webpackChunkName: "pages/list/currency" */))
const _63b18642 = () => interopDefault(import('../nuxt/pages/list/journal-entry.vue' /* webpackChunkName: "pages/list/journal-entry" */))
const _72d16ba8 = () => interopDefault(import('../nuxt/pages/list/menu.vue' /* webpackChunkName: "pages/list/menu" */))
const _0362df6a = () => interopDefault(import('../nuxt/pages/list/paymentmethod.vue' /* webpackChunkName: "pages/list/paymentmethod" */))
const _20c60496 = () => interopDefault(import('../nuxt/pages/list/paymentterm.vue' /* webpackChunkName: "pages/list/paymentterm" */))
const _0a0f15c7 = () => interopDefault(import('../nuxt/pages/list/permissions.vue' /* webpackChunkName: "pages/list/permissions" */))
const _2d66862f = () => interopDefault(import('../nuxt/pages/list/price-list.vue' /* webpackChunkName: "pages/list/price-list" */))
const _1d992b40 = () => interopDefault(import('../nuxt/pages/list/recurring.vue' /* webpackChunkName: "pages/list/recurring" */))
const _04915f0d = () => interopDefault(import('../nuxt/pages/list/reporting-period.vue' /* webpackChunkName: "pages/list/reporting-period" */))
const _7f0587c0 = () => interopDefault(import('../nuxt/pages/list/roles.vue' /* webpackChunkName: "pages/list/roles" */))
const _72595900 = () => interopDefault(import('../nuxt/pages/list/setup.vue' /* webpackChunkName: "pages/list/setup" */))
const _342a5f24 = () => interopDefault(import('../nuxt/pages/list/tax.vue' /* webpackChunkName: "pages/list/tax" */))
const _7f3ae98b = () => interopDefault(import('../nuxt/pages/list/users.vue' /* webpackChunkName: "pages/list/users" */))
const _e343c180 = () => interopDefault(import('../nuxt/pages/reports/bank.vue' /* webpackChunkName: "pages/reports/bank" */))
const _27c0f752 = () => interopDefault(import('../nuxt/pages/reports/bp.vue' /* webpackChunkName: "pages/reports/bp" */))
const _435d5097 = () => interopDefault(import('../nuxt/pages/reports/item.vue' /* webpackChunkName: "pages/reports/item" */))
const _6a96a023 = () => interopDefault(import('../nuxt/pages/tools/audit.vue' /* webpackChunkName: "pages/tools/audit" */))
const _db1809c8 = () => interopDefault(import('../nuxt/pages/tools/export.vue' /* webpackChunkName: "pages/tools/export" */))
const _04a8fa8d = () => interopDefault(import('../nuxt/pages/tools/import.vue' /* webpackChunkName: "pages/tools/import" */))
const _1dc6c12d = () => interopDefault(import('../nuxt/pages/app/bank/deposit.vue' /* webpackChunkName: "pages/app/bank/deposit" */))
const _10e39c1d = () => interopDefault(import('../nuxt/pages/app/bank/reconcile.vue' /* webpackChunkName: "pages/app/bank/reconcile" */))
const _0c88e4ec = () => interopDefault(import('../nuxt/pages/app/bank/transfer.vue' /* webpackChunkName: "pages/app/bank/transfer" */))
const _250edeea = () => interopDefault(import('../nuxt/pages/app/bank/withdraw.vue' /* webpackChunkName: "pages/app/bank/withdraw" */))
const _53558228 = () => interopDefault(import('../nuxt/pages/app/form/accounting/account.vue' /* webpackChunkName: "pages/app/form/accounting/account" */))
const _1a6459cd = () => interopDefault(import('../nuxt/pages/app/form/accounting/reconcile.vue' /* webpackChunkName: "pages/app/form/accounting/reconcile" */))
const _8547bb34 = () => interopDefault(import('../nuxt/pages/app/form/contact/customer.vue' /* webpackChunkName: "pages/app/form/contact/customer" */))
const _61290260 = () => interopDefault(import('../nuxt/pages/app/form/contact/vendor.vue' /* webpackChunkName: "pages/app/form/contact/vendor" */))
const _454aa566 = () => interopDefault(import('../nuxt/pages/app/form/item/item.vue' /* webpackChunkName: "pages/app/form/item/item" */))
const _317d8d96 = () => interopDefault(import('../nuxt/pages/app/form/item/price-list.vue' /* webpackChunkName: "pages/app/form/item/price-list" */))
const _120761d8 = () => interopDefault(import('../nuxt/pages/app/form/payroll/contractor.vue' /* webpackChunkName: "pages/app/form/payroll/contractor" */))
const _35708f5e = () => interopDefault(import('../nuxt/pages/app/form/payroll/employee.vue' /* webpackChunkName: "pages/app/form/payroll/employee" */))
const _56efa298 = () => interopDefault(import('../nuxt/pages/app/form/purchase/creditmemo.vue' /* webpackChunkName: "pages/app/form/purchase/creditmemo" */))
const _5a392999 = () => interopDefault(import('../nuxt/pages/app/form/purchase/delivery.vue' /* webpackChunkName: "pages/app/form/purchase/delivery" */))
const _7fd3c81e = () => interopDefault(import('../nuxt/pages/app/form/purchase/dp.vue' /* webpackChunkName: "pages/app/form/purchase/dp" */))
const _21a066d0 = () => interopDefault(import('../nuxt/pages/app/form/purchase/invoice.vue' /* webpackChunkName: "pages/app/form/purchase/invoice" */))
const _f2455e0e = () => interopDefault(import('../nuxt/pages/app/form/purchase/order.vue' /* webpackChunkName: "pages/app/form/purchase/order" */))
const _727eb21e = () => interopDefault(import('../nuxt/pages/app/form/purchase/payment.vue' /* webpackChunkName: "pages/app/form/purchase/payment" */))
const _45cfc127 = () => interopDefault(import('../nuxt/pages/app/form/purchase/quote.vue' /* webpackChunkName: "pages/app/form/purchase/quote" */))
const _7a0b2c15 = () => interopDefault(import('../nuxt/pages/app/form/purchase/return.vue' /* webpackChunkName: "pages/app/form/purchase/return" */))
const _15a3ece2 = () => interopDefault(import('../nuxt/pages/app/form/sales/creditmemo.vue' /* webpackChunkName: "pages/app/form/sales/creditmemo" */))
const _4402e250 = () => interopDefault(import('../nuxt/pages/app/form/sales/delivery.vue' /* webpackChunkName: "pages/app/form/sales/delivery" */))
const _622b2c30 = () => interopDefault(import('../nuxt/pages/app/form/sales/dp.vue' /* webpackChunkName: "pages/app/form/sales/dp" */))
const _0f80a101 = () => interopDefault(import('../nuxt/pages/app/form/sales/invoice.vue' /* webpackChunkName: "pages/app/form/sales/invoice" */))
const _3afa2ba2 = () => interopDefault(import('../nuxt/pages/app/form/sales/order.vue' /* webpackChunkName: "pages/app/form/sales/order" */))
const _31dd094c = () => interopDefault(import('../nuxt/pages/app/form/sales/payment.vue' /* webpackChunkName: "pages/app/form/sales/payment" */))
const _0c26c860 = () => interopDefault(import('../nuxt/pages/app/form/sales/quote.vue' /* webpackChunkName: "pages/app/form/sales/quote" */))
const _4989a68c = () => interopDefault(import('../nuxt/pages/app/form/sales/return.vue' /* webpackChunkName: "pages/app/form/sales/return" */))
const _796e3bce = () => interopDefault(import('../nuxt/pages/index.vue' /* webpackChunkName: "pages/index" */))

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
    component: _39d10f55,
    name: "list"
  }, {
    path: "/app/accounting",
    component: _64643b77,
    name: "app-accounting",
    children: [{
      path: "account",
      component: _31e216d6,
      name: "app-accounting-account"
    }, {
      path: "reconcile",
      component: _38f16d94,
      name: "app-accounting-reconcile"
    }]
  }, {
    path: "/app/contact",
    component: _15d81ba4,
    name: "app-contact",
    children: [{
      path: "customer",
      component: _e5ada362,
      name: "app-contact-customer"
    }, {
      path: "customer-detail",
      component: _5b91debf,
      name: "app-contact-customer-detail"
    }, {
      path: "vendor",
      component: _a8a71e0e,
      name: "app-contact-vendor"
    }, {
      path: "vendor-detail",
      component: _7cc828d5,
      name: "app-contact-vendor-detail"
    }]
  }, {
    path: "/app/item",
    component: _5f77e475,
    name: "app-item",
    children: [{
      path: "list",
      component: _96ee94b0,
      name: "app-item-list"
    }, {
      path: "price-list",
      component: _d9c2e7a8,
      name: "app-item-price-list"
    }, {
      path: "form/fitem",
      component: _7cd2c6f8,
      name: "app-item-form-fitem"
    }, {
      path: "form/fprice-list",
      component: _31ba6547,
      name: "app-item-form-fprice-list"
    }]
  }, {
    path: "/app/payroll",
    component: _a9453e9a,
    name: "app-payroll",
    children: [{
      path: "contractor",
      component: _22c11781,
      name: "app-payroll-contractor"
    }, {
      path: "employee",
      component: _95d6778c,
      name: "app-payroll-employee"
    }, {
      path: "overview",
      component: _7b7724f6,
      name: "app-payroll-overview"
    }]
  }, {
    path: "/app/project",
    component: _8ac216b2,
    name: "app-project"
  }, {
    path: "/app/purchase",
    component: _1418db3a,
    name: "app-purchase",
    children: [{
      path: "invoice",
      component: _82064efe,
      name: "app-purchase-invoice"
    }, {
      path: "list",
      component: _811ae70c,
      name: "app-purchase-list"
    }, {
      path: "overview",
      component: _019321d5,
      name: "app-purchase-overview"
    }]
  }, {
    path: "/app/reports",
    component: _36058f66,
    name: "app-reports",
    children: [{
      path: "list",
      component: _2613ee60,
      name: "app-reports-list"
    }, {
      path: "performance",
      component: _65aef624,
      name: "app-reports-performance"
    }]
  }, {
    path: "/app/sales",
    component: _0ccb269a,
    name: "app-sales",
    children: [{
      path: "invoice",
      component: _79b445b8,
      name: "app-sales-invoice"
    }, {
      path: "list",
      component: _421202a3,
      name: "app-sales-list"
    }, {
      path: "overview",
      component: _c46d4904,
      name: "app-sales-overview"
    }]
  }, {
    path: "/app/task",
    component: _00d615e7,
    name: "app-task"
  }, {
    path: "/auth/login",
    component: _ebe2ca14,
    name: "auth-login"
  }, {
    path: "/documents/form",
    component: _605e91ef,
    name: "documents-form"
  }, {
    path: "/documents/list",
    component: _d7508e6e,
    name: "documents-list"
  }, {
    path: "/documents/view",
    component: _e92c3160,
    name: "documents-view"
  }, {
    path: "/home/business-overview",
    component: _67516b2c,
    name: "home-business-overview"
  }, {
    path: "/home/getthingdone",
    component: _69def306,
    name: "home-getthingdone"
  }, {
    path: "/list/account-category",
    component: _78bb6e6b,
    name: "list-account-category"
  }, {
    path: "/list/accountmapping",
    component: _2f6b30e4,
    name: "list-accountmapping"
  }, {
    path: "/list/attachment",
    component: _6f8d9fa0,
    name: "list-attachment"
  }, {
    path: "/list/budgeting",
    component: _05469200,
    name: "list-budgeting"
  }, {
    path: "/list/closing",
    component: _03e20af8,
    name: "list-closing"
  }, {
    path: "/list/company",
    component: _4b447280,
    name: "list-company"
  }, {
    path: "/list/currency",
    component: _5aa9995e,
    name: "list-currency"
  }, {
    path: "/list/journal-entry",
    component: _63b18642,
    name: "list-journal-entry"
  }, {
    path: "/list/menu",
    component: _72d16ba8,
    name: "list-menu"
  }, {
    path: "/list/paymentmethod",
    component: _0362df6a,
    name: "list-paymentmethod"
  }, {
    path: "/list/paymentterm",
    component: _20c60496,
    name: "list-paymentterm"
  }, {
    path: "/list/permissions",
    component: _0a0f15c7,
    name: "list-permissions"
  }, {
    path: "/list/price-list",
    component: _2d66862f,
    name: "list-price-list"
  }, {
    path: "/list/recurring",
    component: _1d992b40,
    name: "list-recurring"
  }, {
    path: "/list/reporting-period",
    component: _04915f0d,
    name: "list-reporting-period"
  }, {
    path: "/list/roles",
    component: _7f0587c0,
    name: "list-roles"
  }, {
    path: "/list/setup",
    component: _72595900,
    name: "list-setup"
  }, {
    path: "/list/tax",
    component: _342a5f24,
    name: "list-tax"
  }, {
    path: "/list/users",
    component: _7f3ae98b,
    name: "list-users"
  }, {
    path: "/reports/bank",
    component: _e343c180,
    name: "reports-bank"
  }, {
    path: "/reports/bp",
    component: _27c0f752,
    name: "reports-bp"
  }, {
    path: "/reports/item",
    component: _435d5097,
    name: "reports-item"
  }, {
    path: "/tools/audit",
    component: _6a96a023,
    name: "tools-audit"
  }, {
    path: "/tools/export",
    component: _db1809c8,
    name: "tools-export"
  }, {
    path: "/tools/import",
    component: _04a8fa8d,
    name: "tools-import"
  }, {
    path: "/app/bank/deposit",
    component: _1dc6c12d,
    name: "app-bank-deposit"
  }, {
    path: "/app/bank/reconcile",
    component: _10e39c1d,
    name: "app-bank-reconcile"
  }, {
    path: "/app/bank/transfer",
    component: _0c88e4ec,
    name: "app-bank-transfer"
  }, {
    path: "/app/bank/withdraw",
    component: _250edeea,
    name: "app-bank-withdraw"
  }, {
    path: "/app/form/accounting/account",
    component: _53558228,
    name: "app-form-accounting-account"
  }, {
    path: "/app/form/accounting/reconcile",
    component: _1a6459cd,
    name: "app-form-accounting-reconcile"
  }, {
    path: "/app/form/contact/customer",
    component: _8547bb34,
    name: "app-form-contact-customer"
  }, {
    path: "/app/form/contact/vendor",
    component: _61290260,
    name: "app-form-contact-vendor"
  }, {
    path: "/app/form/item/item",
    component: _454aa566,
    name: "app-form-item-item"
  }, {
    path: "/app/form/item/price-list",
    component: _317d8d96,
    name: "app-form-item-price-list"
  }, {
    path: "/app/form/payroll/contractor",
    component: _120761d8,
    name: "app-form-payroll-contractor"
  }, {
    path: "/app/form/payroll/employee",
    component: _35708f5e,
    name: "app-form-payroll-employee"
  }, {
    path: "/app/form/purchase/creditmemo",
    component: _56efa298,
    name: "app-form-purchase-creditmemo"
  }, {
    path: "/app/form/purchase/delivery",
    component: _5a392999,
    name: "app-form-purchase-delivery"
  }, {
    path: "/app/form/purchase/dp",
    component: _7fd3c81e,
    name: "app-form-purchase-dp"
  }, {
    path: "/app/form/purchase/invoice",
    component: _21a066d0,
    name: "app-form-purchase-invoice"
  }, {
    path: "/app/form/purchase/order",
    component: _f2455e0e,
    name: "app-form-purchase-order"
  }, {
    path: "/app/form/purchase/payment",
    component: _727eb21e,
    name: "app-form-purchase-payment"
  }, {
    path: "/app/form/purchase/quote",
    component: _45cfc127,
    name: "app-form-purchase-quote"
  }, {
    path: "/app/form/purchase/return",
    component: _7a0b2c15,
    name: "app-form-purchase-return"
  }, {
    path: "/app/form/sales/creditmemo",
    component: _15a3ece2,
    name: "app-form-sales-creditmemo"
  }, {
    path: "/app/form/sales/delivery",
    component: _4402e250,
    name: "app-form-sales-delivery"
  }, {
    path: "/app/form/sales/dp",
    component: _622b2c30,
    name: "app-form-sales-dp"
  }, {
    path: "/app/form/sales/invoice",
    component: _0f80a101,
    name: "app-form-sales-invoice"
  }, {
    path: "/app/form/sales/order",
    component: _3afa2ba2,
    name: "app-form-sales-order"
  }, {
    path: "/app/form/sales/payment",
    component: _31dd094c,
    name: "app-form-sales-payment"
  }, {
    path: "/app/form/sales/quote",
    component: _0c26c860,
    name: "app-form-sales-quote"
  }, {
    path: "/app/form/sales/return",
    component: _4989a68c,
    name: "app-form-sales-return"
  }, {
    path: "/",
    component: _796e3bce,
    name: "index"
  }, {
    path: "/__nuxt_laravel",
    component: _796e3bce,
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
