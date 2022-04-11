import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _3565828e = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\index.vue' /* webpackChunkName: "pages/dashboard/index" */))
const _ed3554be = () => interopDefault(import('..\\nuxt\\pages\\auth\\login.vue' /* webpackChunkName: "pages/auth/login" */))
const _6d09e893 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\posts\\index.vue' /* webpackChunkName: "pages/dashboard/posts/index" */))
const _b2935410 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tasks\\index.vue' /* webpackChunkName: "pages/dashboard/tasks/index" */))
const _0529d33e = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\users\\index.vue' /* webpackChunkName: "pages/dashboard/users/index" */))
const _6bb8a93d = () => interopDefault(import('..\\nuxt\\pages\\documents\\view.vue' /* webpackChunkName: "pages/documents/view" */))
const _2af319fc = () => interopDefault(import('..\\nuxt\\pages\\pages\\about-us.vue' /* webpackChunkName: "pages/pages/about-us" */))
const _388e5b36 = () => interopDefault(import('..\\nuxt\\pages\\pages\\booking.vue' /* webpackChunkName: "pages/pages/booking" */))
const _3e4f5322 = () => interopDefault(import('..\\nuxt\\pages\\pages\\category.vue' /* webpackChunkName: "pages/pages/category" */))
const _6586c40f = () => interopDefault(import('..\\nuxt\\pages\\pages\\contact-us.vue' /* webpackChunkName: "pages/pages/contact-us" */))
const _43d1c8a2 = () => interopDefault(import('..\\nuxt\\pages\\pages\\faq.vue' /* webpackChunkName: "pages/pages/faq" */))
const _bc37a88a = () => interopDefault(import('..\\nuxt\\pages\\pages\\privacy-policy.vue' /* webpackChunkName: "pages/pages/privacy-policy" */))
const _1c3b8cda = () => interopDefault(import('..\\nuxt\\pages\\pages\\terms.vue' /* webpackChunkName: "pages/pages/terms" */))
const _1f2cfcbc = () => interopDefault(import('..\\nuxt\\pages\\student\\announcement.vue' /* webpackChunkName: "pages/student/announcement" */))
const _1b39dee2 = () => interopDefault(import('..\\nuxt\\pages\\student\\print.vue' /* webpackChunkName: "pages/student/print" */))
const _11b72304 = () => interopDefault(import('..\\nuxt\\pages\\student\\register.vue' /* webpackChunkName: "pages/student/register" */))
const _4e8422ce = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\bp\\contact.vue' /* webpackChunkName: "pages/dashboard/bp/contact" */))
const _3f9018b8 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\documents\\form.vue' /* webpackChunkName: "pages/dashboard/documents/form" */))
const _73893f92 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\documents\\list.vue' /* webpackChunkName: "pages/dashboard/documents/list" */))
const _6a90b190 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\account.vue' /* webpackChunkName: "pages/dashboard/financial/account" */))
const _4511b6ba = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\account-category.vue' /* webpackChunkName: "pages/dashboard/financial/account-category" */))
const _694d1545 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\reporting-period.vue' /* webpackChunkName: "pages/dashboard/financial/reporting-period" */))
const _7f19912b = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\inventory\\item.vue' /* webpackChunkName: "pages/dashboard/inventory/item" */))
const _06a24466 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\posts\\category.vue' /* webpackChunkName: "pages/dashboard/posts/category" */))
const _67758a88 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\invoice.vue' /* webpackChunkName: "pages/dashboard/purchase/invoice" */))
const _6c59cae9 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\order.vue' /* webpackChunkName: "pages/dashboard/purchase/order" */))
const _3f0664e1 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\payment.vue' /* webpackChunkName: "pages/dashboard/purchase/payment" */))
const _2b4c3b17 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\quote.vue' /* webpackChunkName: "pages/dashboard/purchase/quote" */))
const _140d3c13 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\receipt.vue' /* webpackChunkName: "pages/dashboard/purchase/receipt" */))
const _441df025 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\return.vue' /* webpackChunkName: "pages/dashboard/purchase/return" */))
const _48c0873c = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\delivery.vue' /* webpackChunkName: "pages/dashboard/sales/delivery" */))
const _f976a2d6 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\invoice.vue' /* webpackChunkName: "pages/dashboard/sales/invoice" */))
const _19cc8e36 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\order.vue' /* webpackChunkName: "pages/dashboard/sales/order" */))
const _5ad588ee = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\payment.vue' /* webpackChunkName: "pages/dashboard/sales/payment" */))
const _4e820338 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\quote.vue' /* webpackChunkName: "pages/dashboard/sales/quote" */))
const _45039678 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\return.vue' /* webpackChunkName: "pages/dashboard/sales/return" */))
const _62a240b6 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\menu.vue' /* webpackChunkName: "pages/dashboard/settings/menu" */))
const _0f458ffd = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\permissions.vue' /* webpackChunkName: "pages/dashboard/settings/permissions" */))
const _64596276 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\roles.vue' /* webpackChunkName: "pages/dashboard/settings/roles" */))
const _a7b1a394 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\setup.vue' /* webpackChunkName: "pages/dashboard/settings/setup" */))
const _2437e9c7 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tasks\\show.vue' /* webpackChunkName: "pages/dashboard/tasks/show" */))
const _74b8d765 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\users\\current.vue' /* webpackChunkName: "pages/dashboard/users/current" */))
const _0c179ab2 = () => interopDefault(import('..\\nuxt\\pages\\index.vue' /* webpackChunkName: "pages/index" */))

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
    component: _3565828e,
    name: "dashboard"
  }, {
    path: "/auth/login",
    component: _ed3554be,
    name: "auth-login"
  }, {
    path: "/dashboard/posts",
    component: _6d09e893,
    name: "dashboard-posts"
  }, {
    path: "/dashboard/tasks",
    component: _b2935410,
    name: "dashboard-tasks"
  }, {
    path: "/dashboard/users",
    component: _0529d33e,
    name: "dashboard-users"
  }, {
    path: "/documents/view",
    component: _6bb8a93d,
    name: "documents-view"
  }, {
    path: "/pages/about-us",
    component: _2af319fc,
    name: "pages-about-us"
  }, {
    path: "/pages/booking",
    component: _388e5b36,
    name: "pages-booking"
  }, {
    path: "/pages/category",
    component: _3e4f5322,
    name: "pages-category"
  }, {
    path: "/pages/contact-us",
    component: _6586c40f,
    name: "pages-contact-us"
  }, {
    path: "/pages/faq",
    component: _43d1c8a2,
    name: "pages-faq"
  }, {
    path: "/pages/privacy-policy",
    component: _bc37a88a,
    name: "pages-privacy-policy"
  }, {
    path: "/pages/terms",
    component: _1c3b8cda,
    name: "pages-terms"
  }, {
    path: "/student/announcement",
    component: _1f2cfcbc,
    name: "student-announcement"
  }, {
    path: "/student/print",
    component: _1b39dee2,
    name: "student-print"
  }, {
    path: "/student/register",
    component: _11b72304,
    name: "student-register"
  }, {
    path: "/dashboard/bp/contact",
    component: _4e8422ce,
    name: "dashboard-bp-contact"
  }, {
    path: "/dashboard/documents/form",
    component: _3f9018b8,
    name: "dashboard-documents-form"
  }, {
    path: "/dashboard/documents/list",
    component: _73893f92,
    name: "dashboard-documents-list"
  }, {
    path: "/dashboard/financial/account",
    component: _6a90b190,
    name: "dashboard-financial-account"
  }, {
    path: "/dashboard/financial/account-category",
    component: _4511b6ba,
    name: "dashboard-financial-account-category"
  }, {
    path: "/dashboard/financial/reporting-period",
    component: _694d1545,
    name: "dashboard-financial-reporting-period"
  }, {
    path: "/dashboard/inventory/item",
    component: _7f19912b,
    name: "dashboard-inventory-item"
  }, {
    path: "/dashboard/posts/category",
    component: _06a24466,
    name: "dashboard-posts-category"
  }, {
    path: "/dashboard/purchase/invoice",
    component: _67758a88,
    name: "dashboard-purchase-invoice"
  }, {
    path: "/dashboard/purchase/order",
    component: _6c59cae9,
    name: "dashboard-purchase-order"
  }, {
    path: "/dashboard/purchase/payment",
    component: _3f0664e1,
    name: "dashboard-purchase-payment"
  }, {
    path: "/dashboard/purchase/quote",
    component: _2b4c3b17,
    name: "dashboard-purchase-quote"
  }, {
    path: "/dashboard/purchase/receipt",
    component: _140d3c13,
    name: "dashboard-purchase-receipt"
  }, {
    path: "/dashboard/purchase/return",
    component: _441df025,
    name: "dashboard-purchase-return"
  }, {
    path: "/dashboard/sales/delivery",
    component: _48c0873c,
    name: "dashboard-sales-delivery"
  }, {
    path: "/dashboard/sales/invoice",
    component: _f976a2d6,
    name: "dashboard-sales-invoice"
  }, {
    path: "/dashboard/sales/order",
    component: _19cc8e36,
    name: "dashboard-sales-order"
  }, {
    path: "/dashboard/sales/payment",
    component: _5ad588ee,
    name: "dashboard-sales-payment"
  }, {
    path: "/dashboard/sales/quote",
    component: _4e820338,
    name: "dashboard-sales-quote"
  }, {
    path: "/dashboard/sales/return",
    component: _45039678,
    name: "dashboard-sales-return"
  }, {
    path: "/dashboard/settings/menu",
    component: _62a240b6,
    name: "dashboard-settings-menu"
  }, {
    path: "/dashboard/settings/permissions",
    component: _0f458ffd,
    name: "dashboard-settings-permissions"
  }, {
    path: "/dashboard/settings/roles",
    component: _64596276,
    name: "dashboard-settings-roles"
  }, {
    path: "/dashboard/settings/setup",
    component: _a7b1a394,
    name: "dashboard-settings-setup"
  }, {
    path: "/dashboard/tasks/show",
    component: _2437e9c7,
    name: "dashboard-tasks-show"
  }, {
    path: "/dashboard/users/current",
    component: _74b8d765,
    name: "dashboard-users-current"
  }, {
    path: "/",
    component: _0c179ab2,
    name: "index"
  }, {
    path: "/__nuxt_laravel",
    component: _0c179ab2,
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
