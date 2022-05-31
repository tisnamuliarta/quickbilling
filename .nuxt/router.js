import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _72c2a8fb = () => interopDefault(import('..\\nuxt\\pages\\list\\index.vue' /* webpackChunkName: "pages/list/index" */))
const _0a81983d = () => interopDefault(import('..\\nuxt\\pages\\app\\accounting.vue' /* webpackChunkName: "pages/app/accounting" */))
const _3e4c7280 = () => interopDefault(import('..\\nuxt\\pages\\app\\accounting\\account.vue' /* webpackChunkName: "pages/app/accounting/account" */))
const _d43386be = () => interopDefault(import('..\\nuxt\\pages\\app\\accounting\\reconcile.vue' /* webpackChunkName: "pages/app/accounting/reconcile" */))
const _047989b0 = () => interopDefault(import('..\\nuxt\\pages\\app\\contact.vue' /* webpackChunkName: "pages/app/contact" */))
const _2eaaf386 = () => interopDefault(import('..\\nuxt\\pages\\app\\contact\\customer.vue' /* webpackChunkName: "pages/app/contact/customer" */))
const _604dc4b0 = () => interopDefault(import('..\\nuxt\\pages\\app\\contact\\customer-detail.vue' /* webpackChunkName: "pages/app/contact/customer-detail" */))
const _7b07d020 = () => interopDefault(import('..\\nuxt\\pages\\app\\contact\\vendor.vue' /* webpackChunkName: "pages/app/contact/vendor" */))
const _2c740dfe = () => interopDefault(import('..\\nuxt\\pages\\app\\contact\\vendor-detail.vue' /* webpackChunkName: "pages/app/contact/vendor-detail" */))
const _6a18648a = () => interopDefault(import('..\\nuxt\\pages\\app\\item.vue' /* webpackChunkName: "pages/app/item" */))
const _57f76499 = () => interopDefault(import('..\\nuxt\\pages\\app\\item\\list.vue' /* webpackChunkName: "pages/app/item/list" */))
const _95defb46 = () => interopDefault(import('..\\nuxt\\pages\\app\\item\\price-list.vue' /* webpackChunkName: "pages/app/item/price-list" */))
const _924913ec = () => interopDefault(import('..\\nuxt\\pages\\app\\item\\form\\fitem.vue' /* webpackChunkName: "pages/app/item/form/fitem" */))
const _3f18024e = () => interopDefault(import('..\\nuxt\\pages\\app\\item\\form\\fprice-list.vue' /* webpackChunkName: "pages/app/item/form/fprice-list" */))
const _97e6aca6 = () => interopDefault(import('..\\nuxt\\pages\\app\\payroll.vue' /* webpackChunkName: "pages/app/payroll" */))
const _2c1c7da2 = () => interopDefault(import('..\\nuxt\\pages\\app\\payroll\\contractor.vue' /* webpackChunkName: "pages/app/payroll/contractor" */))
const _60a53e9b = () => interopDefault(import('..\\nuxt\\pages\\app\\payroll\\employee.vue' /* webpackChunkName: "pages/app/payroll/employee" */))
const _6dd4e7e6 = () => interopDefault(import('..\\nuxt\\pages\\app\\payroll\\overview.vue' /* webpackChunkName: "pages/app/payroll/overview" */))
const _796384be = () => interopDefault(import('..\\nuxt\\pages\\app\\project.vue' /* webpackChunkName: "pages/app/project" */))
const _032d68a9 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase.vue' /* webpackChunkName: "pages/app/purchase" */))
const _671c2e58 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\invoice.vue' /* webpackChunkName: "pages/app/purchase/invoice" */))
const _6f209507 = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\list.vue' /* webpackChunkName: "pages/app/purchase/list" */))
const _ba7fc83c = () => interopDefault(import('..\\nuxt\\pages\\app\\purchase\\overview.vue' /* webpackChunkName: "pages/app/purchase/overview" */))
const _24a6fd72 = () => interopDefault(import('..\\nuxt\\pages\\app\\reports.vue' /* webpackChunkName: "pages/app/reports" */))
const _7a063f25 = () => interopDefault(import('..\\nuxt\\pages\\app\\reports\\list.vue' /* webpackChunkName: "pages/app/reports/list" */))
const _6b5374f9 = () => interopDefault(import('..\\nuxt\\pages\\app\\reports\\performance.vue' /* webpackChunkName: "pages/app/reports/performance" */))
const _10cc6614 = () => interopDefault(import('..\\nuxt\\pages\\app\\sales.vue' /* webpackChunkName: "pages/app/sales" */))
const _3fdc586e = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\invoice.vue' /* webpackChunkName: "pages/app/sales/invoice" */))
const _19eed39c = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\list.vue' /* webpackChunkName: "pages/app/sales/list" */))
const _031d8f8d = () => interopDefault(import('..\\nuxt\\pages\\app\\sales\\overview.vue' /* webpackChunkName: "pages/app/sales/overview" */))
const _6c51ff2d = () => interopDefault(import('..\\nuxt\\pages\\app\\task.vue' /* webpackChunkName: "pages/app/task" */))
const _eedd1670 = () => interopDefault(import('..\\nuxt\\pages\\auth\\login.vue' /* webpackChunkName: "pages/auth/login" */))
const _fe50f0fa = () => interopDefault(import('..\\nuxt\\pages\\documents\\form.vue' /* webpackChunkName: "pages/documents/form" */))
const _965ea346 = () => interopDefault(import('..\\nuxt\\pages\\documents\\list.vue' /* webpackChunkName: "pages/documents/list" */))
const _a83a4638 = () => interopDefault(import('..\\nuxt\\pages\\documents\\view.vue' /* webpackChunkName: "pages/documents/view" */))
const _d91e1fa4 = () => interopDefault(import('..\\nuxt\\pages\\home\\business-overview.vue' /* webpackChunkName: "pages/home/business-overview" */))
const _317dc2c2 = () => interopDefault(import('..\\nuxt\\pages\\home\\getthingdone.vue' /* webpackChunkName: "pages/home/getthingdone" */))
const _18317105 = () => interopDefault(import('..\\nuxt\\pages\\list\\account-category.vue' /* webpackChunkName: "pages/list/account-category" */))
const _7ab448a8 = () => interopDefault(import('..\\nuxt\\pages\\list\\accountmapping.vue' /* webpackChunkName: "pages/list/accountmapping" */))
const _a2b8a36c = () => interopDefault(import('..\\nuxt\\pages\\list\\attachment.vue' /* webpackChunkName: "pages/list/attachment" */))
const _5a322eb4 = () => interopDefault(import('..\\nuxt\\pages\\list\\budgeting.vue' /* webpackChunkName: "pages/list/budgeting" */))
const _725859c4 = () => interopDefault(import('..\\nuxt\\pages\\list\\closing.vue' /* webpackChunkName: "pages/list/closing" */))
const _c560e234 = () => interopDefault(import('..\\nuxt\\pages\\list\\company.vue' /* webpackChunkName: "pages/list/company" */))
const _141e5410 = () => interopDefault(import('..\\nuxt\\pages\\list\\currency.vue' /* webpackChunkName: "pages/list/currency" */))
const _de605ef6 = () => interopDefault(import('..\\nuxt\\pages\\list\\journal-entry.vue' /* webpackChunkName: "pages/list/journal-entry" */))
const _75d8e3c6 = () => interopDefault(import('..\\nuxt\\pages\\list\\menu.vue' /* webpackChunkName: "pages/list/menu" */))
const _73e919e0 = () => interopDefault(import('..\\nuxt\\pages\\list\\paymentmethod.vue' /* webpackChunkName: "pages/list/paymentmethod" */))
const _52fb7a4a = () => interopDefault(import('..\\nuxt\\pages\\list\\paymentterm.vue' /* webpackChunkName: "pages/list/paymentterm" */))
const _1e174a26 = () => interopDefault(import('..\\nuxt\\pages\\list\\permissions.vue' /* webpackChunkName: "pages/list/permissions" */))
const _13d10449 = () => interopDefault(import('..\\nuxt\\pages\\list\\price-list.vue' /* webpackChunkName: "pages/list/price-list" */))
const _82587df4 = () => interopDefault(import('..\\nuxt\\pages\\list\\recurring.vue' /* webpackChunkName: "pages/list/recurring" */))
const _b7f13cb2 = () => interopDefault(import('..\\nuxt\\pages\\list\\reporting-period.vue' /* webpackChunkName: "pages/list/reporting-period" */))
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
const _20216a6a = () => interopDefault(import('..\\nuxt\\pages\\app\\bank\\deposit.vue' /* webpackChunkName: "pages/app/bank/deposit" */))
const _318e2bcc = () => interopDefault(import('..\\nuxt\\pages\\app\\bank\\reconcile.vue' /* webpackChunkName: "pages/app/bank/reconcile" */))
const _5583634f = () => interopDefault(import('..\\nuxt\\pages\\app\\bank\\transfer.vue' /* webpackChunkName: "pages/app/bank/transfer" */))
const _36730eee = () => interopDefault(import('..\\nuxt\\pages\\app\\bank\\withdraw.vue' /* webpackChunkName: "pages/app/bank/withdraw" */))
const _03226208 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\accounting\\account.vue' /* webpackChunkName: "pages/app/form/accounting/account" */))
const _bb4bb446 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\accounting\\reconcile.vue' /* webpackChunkName: "pages/app/form/accounting/reconcile" */))
const _211cdac2 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\contact\\customer.vue' /* webpackChunkName: "pages/app/form/contact/customer" */))
const _ed12c3a8 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\contact\\vendor.vue' /* webpackChunkName: "pages/app/form/contact/vendor" */))
const _21591e5c = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\item\\item.vue' /* webpackChunkName: "pages/app/form/item/item" */))
const _653278be = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\item\\price-list.vue' /* webpackChunkName: "pages/app/form/item/price-list" */))
const _49b185de = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\payroll\\contractor.vue' /* webpackChunkName: "pages/app/form/payroll/contractor" */))
const _531725d7 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\payroll\\employee.vue' /* webpackChunkName: "pages/app/form/payroll/employee" */))
const _076167c0 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\purchase\\creditmemo.vue' /* webpackChunkName: "pages/app/form/purchase/creditmemo" */))
const _fdf4d5be = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\purchase\\delivery.vue' /* webpackChunkName: "pages/app/form/purchase/delivery" */))
const _655bcd79 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\purchase\\dp.vue' /* webpackChunkName: "pages/app/form/purchase/dp" */))
const _82385fe0 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\purchase\\invoice.vue' /* webpackChunkName: "pages/app/form/purchase/invoice" */))
const _702bd271 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\purchase\\order.vue' /* webpackChunkName: "pages/app/form/purchase/order" */))
const _d316ab2e = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\purchase\\payment.vue' /* webpackChunkName: "pages/app/form/purchase/payment" */))
const _2f1e429f = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\purchase\\quote.vue' /* webpackChunkName: "pages/app/form/purchase/quote" */))
const _8ae64cc6 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\purchase\\return.vue' /* webpackChunkName: "pages/app/form/purchase/return" */))
const _18846903 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\sales\\creditmemo.vue' /* webpackChunkName: "pages/app/form/sales/creditmemo" */))
const _4c928ec4 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\sales\\delivery.vue' /* webpackChunkName: "pages/app/form/sales/delivery" */))
const _93fd0548 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\sales\\dp.vue' /* webpackChunkName: "pages/app/form/sales/dp" */))
const _30cf960d = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\sales\\invoice.vue' /* webpackChunkName: "pages/app/form/sales/invoice" */))
const _715af3ae = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\sales\\order.vue' /* webpackChunkName: "pages/app/form/sales/order" */))
const _08607066 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\sales\\payment.vue' /* webpackChunkName: "pages/app/form/sales/payment" */))
const _304d63dc = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\sales\\quote.vue' /* webpackChunkName: "pages/app/form/sales/quote" */))
const _417c4000 = () => interopDefault(import('..\\nuxt\\pages\\app\\form\\sales\\return.vue' /* webpackChunkName: "pages/app/form/sales/return" */))
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
    path: "/app/accounting",
    component: _0a81983d,
    name: "app-accounting",
    children: [{
      path: "account",
      component: _3e4c7280,
      name: "app-accounting-account"
    }, {
      path: "reconcile",
      component: _d43386be,
      name: "app-accounting-reconcile"
    }]
  }, {
    path: "/app/contact",
    component: _047989b0,
    name: "app-contact",
    children: [{
      path: "customer",
      component: _2eaaf386,
      name: "app-contact-customer"
    }, {
      path: "customer-detail",
      component: _604dc4b0,
      name: "app-contact-customer-detail"
    }, {
      path: "vendor",
      component: _7b07d020,
      name: "app-contact-vendor"
    }, {
      path: "vendor-detail",
      component: _2c740dfe,
      name: "app-contact-vendor-detail"
    }]
  }, {
    path: "/app/item",
    component: _6a18648a,
    name: "app-item",
    children: [{
      path: "list",
      component: _57f76499,
      name: "app-item-list"
    }, {
      path: "price-list",
      component: _95defb46,
      name: "app-item-price-list"
    }, {
      path: "form/fitem",
      component: _924913ec,
      name: "app-item-form-fitem"
    }, {
      path: "form/fprice-list",
      component: _3f18024e,
      name: "app-item-form-fprice-list"
    }]
  }, {
    path: "/app/payroll",
    component: _97e6aca6,
    name: "app-payroll",
    children: [{
      path: "contractor",
      component: _2c1c7da2,
      name: "app-payroll-contractor"
    }, {
      path: "employee",
      component: _60a53e9b,
      name: "app-payroll-employee"
    }, {
      path: "overview",
      component: _6dd4e7e6,
      name: "app-payroll-overview"
    }]
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
      path: "overview",
      component: _ba7fc83c,
      name: "app-purchase-overview"
    }]
  }, {
    path: "/app/reports",
    component: _24a6fd72,
    name: "app-reports",
    children: [{
      path: "list",
      component: _7a063f25,
      name: "app-reports-list"
    }, {
      path: "performance",
      component: _6b5374f9,
      name: "app-reports-performance"
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
      path: "overview",
      component: _031d8f8d,
      name: "app-sales-overview"
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
    path: "/list/account-category",
    component: _18317105,
    name: "list-account-category"
  }, {
    path: "/list/accountmapping",
    component: _7ab448a8,
    name: "list-accountmapping"
  }, {
    path: "/list/attachment",
    component: _a2b8a36c,
    name: "list-attachment"
  }, {
    path: "/list/budgeting",
    component: _5a322eb4,
    name: "list-budgeting"
  }, {
    path: "/list/closing",
    component: _725859c4,
    name: "list-closing"
  }, {
    path: "/list/company",
    component: _c560e234,
    name: "list-company"
  }, {
    path: "/list/currency",
    component: _141e5410,
    name: "list-currency"
  }, {
    path: "/list/journal-entry",
    component: _de605ef6,
    name: "list-journal-entry"
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
    path: "/list/price-list",
    component: _13d10449,
    name: "list-price-list"
  }, {
    path: "/list/recurring",
    component: _82587df4,
    name: "list-recurring"
  }, {
    path: "/list/reporting-period",
    component: _b7f13cb2,
    name: "list-reporting-period"
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
    path: "/app/form/accounting/account",
    component: _03226208,
    name: "app-form-accounting-account"
  }, {
    path: "/app/form/accounting/reconcile",
    component: _bb4bb446,
    name: "app-form-accounting-reconcile"
  }, {
    path: "/app/form/contact/customer",
    component: _211cdac2,
    name: "app-form-contact-customer"
  }, {
    path: "/app/form/contact/vendor",
    component: _ed12c3a8,
    name: "app-form-contact-vendor"
  }, {
    path: "/app/form/item/item",
    component: _21591e5c,
    name: "app-form-item-item"
  }, {
    path: "/app/form/item/price-list",
    component: _653278be,
    name: "app-form-item-price-list"
  }, {
    path: "/app/form/payroll/contractor",
    component: _49b185de,
    name: "app-form-payroll-contractor"
  }, {
    path: "/app/form/payroll/employee",
    component: _531725d7,
    name: "app-form-payroll-employee"
  }, {
    path: "/app/form/purchase/creditmemo",
    component: _076167c0,
    name: "app-form-purchase-creditmemo"
  }, {
    path: "/app/form/purchase/delivery",
    component: _fdf4d5be,
    name: "app-form-purchase-delivery"
  }, {
    path: "/app/form/purchase/dp",
    component: _655bcd79,
    name: "app-form-purchase-dp"
  }, {
    path: "/app/form/purchase/invoice",
    component: _82385fe0,
    name: "app-form-purchase-invoice"
  }, {
    path: "/app/form/purchase/order",
    component: _702bd271,
    name: "app-form-purchase-order"
  }, {
    path: "/app/form/purchase/payment",
    component: _d316ab2e,
    name: "app-form-purchase-payment"
  }, {
    path: "/app/form/purchase/quote",
    component: _2f1e429f,
    name: "app-form-purchase-quote"
  }, {
    path: "/app/form/purchase/return",
    component: _8ae64cc6,
    name: "app-form-purchase-return"
  }, {
    path: "/app/form/sales/creditmemo",
    component: _18846903,
    name: "app-form-sales-creditmemo"
  }, {
    path: "/app/form/sales/delivery",
    component: _4c928ec4,
    name: "app-form-sales-delivery"
  }, {
    path: "/app/form/sales/dp",
    component: _93fd0548,
    name: "app-form-sales-dp"
  }, {
    path: "/app/form/sales/invoice",
    component: _30cf960d,
    name: "app-form-sales-invoice"
  }, {
    path: "/app/form/sales/order",
    component: _715af3ae,
    name: "app-form-sales-order"
  }, {
    path: "/app/form/sales/payment",
    component: _08607066,
    name: "app-form-sales-payment"
  }, {
    path: "/app/form/sales/quote",
    component: _304d63dc,
    name: "app-form-sales-quote"
  }, {
    path: "/app/form/sales/return",
    component: _417c4000,
    name: "app-form-sales-return"
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
