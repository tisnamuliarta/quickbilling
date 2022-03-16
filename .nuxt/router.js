import Vue from 'vue'
import Router from 'vue-router'
import { normalizeURL, decode } from 'ufo'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _350f25dc = () => interopDefault(import('..\\nuxt\\pages\\about-us.vue' /* webpackChunkName: "pages/about-us" */))
const _6a415c4b = () => interopDefault(import('..\\nuxt\\pages\\booking.vue' /* webpackChunkName: "pages/booking" */))
const _ee4ff408 = () => interopDefault(import('..\\nuxt\\pages\\category.vue' /* webpackChunkName: "pages/category" */))
const _30ae2869 = () => interopDefault(import('..\\nuxt\\pages\\contact-us.vue' /* webpackChunkName: "pages/contact-us" */))
const _cfb61188 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\index.vue' /* webpackChunkName: "pages/dashboard/index" */))
const _0e473588 = () => interopDefault(import('..\\nuxt\\pages\\faq.vue' /* webpackChunkName: "pages/faq" */))
const _639356de = () => interopDefault(import('..\\nuxt\\pages\\item.vue' /* webpackChunkName: "pages/item" */))
const _769b8a3d = () => interopDefault(import('..\\nuxt\\pages\\item-detail.vue' /* webpackChunkName: "pages/item-detail" */))
const _07add315 = () => interopDefault(import('..\\nuxt\\pages\\privacy-policy.vue' /* webpackChunkName: "pages/privacy-policy" */))
const _169ff60e = () => interopDefault(import('..\\nuxt\\pages\\terms.vue' /* webpackChunkName: "pages/terms" */))
const _547f2c33 = () => interopDefault(import('..\\nuxt\\pages\\auth\\login.vue' /* webpackChunkName: "pages/auth/login" */))
const _2e6717b6 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\posts\\index.vue' /* webpackChunkName: "pages/dashboard/posts/index" */))
const _2278e18a = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tasks\\index.vue' /* webpackChunkName: "pages/dashboard/tasks/index" */))
const _fe274260 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\users\\index.vue' /* webpackChunkName: "pages/dashboard/users/index" */))
const _efc46c98 = () => interopDefault(import('..\\nuxt\\pages\\student\\announcement.vue' /* webpackChunkName: "pages/student/announcement" */))
const _2f0aa710 = () => interopDefault(import('..\\nuxt\\pages\\student\\print.vue' /* webpackChunkName: "pages/student/print" */))
const _2758e0e0 = () => interopDefault(import('..\\nuxt\\pages\\student\\register.vue' /* webpackChunkName: "pages/student/register" */))
const _1a73bd4a = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\account.vue' /* webpackChunkName: "pages/dashboard/financial/account" */))
const _441197ca = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\inventory\\contact.vue' /* webpackChunkName: "pages/dashboard/inventory/contact" */))
const _d8ecf74e = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\inventory\\item.vue' /* webpackChunkName: "pages/dashboard/inventory/item" */))
const _111ed0fb = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\posts\\category.vue' /* webpackChunkName: "pages/dashboard/posts/category" */))
const _1fb56a36 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\invoice.vue' /* webpackChunkName: "pages/dashboard/purchase/invoice" */))
const _fe6c83d2 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\order.vue' /* webpackChunkName: "pages/dashboard/purchase/order" */))
const _117376e2 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\payment.vue' /* webpackChunkName: "pages/dashboard/purchase/payment" */))
const _3fbc2e45 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\quote.vue' /* webpackChunkName: "pages/dashboard/purchase/quote" */))
const _6765c87e = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\receipt.vue' /* webpackChunkName: "pages/dashboard/purchase/receipt" */))
const _84a73a92 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\purchase\\return.vue' /* webpackChunkName: "pages/dashboard/purchase/return" */))
const _5d307a6a = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\delivery.vue' /* webpackChunkName: "pages/dashboard/sales/delivery" */))
const _18929da7 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\invoice.vue' /* webpackChunkName: "pages/dashboard/sales/invoice" */))
const _d4e1cc70 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\order.vue' /* webpackChunkName: "pages/dashboard/sales/order" */))
const _1fb91000 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\payment.vue' /* webpackChunkName: "pages/dashboard/sales/payment" */))
const _548189f6 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\quote.vue' /* webpackChunkName: "pages/dashboard/sales/quote" */))
const _41927d26 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\sales\\return.vue' /* webpackChunkName: "pages/dashboard/sales/return" */))
const _101fa070 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\menu.vue' /* webpackChunkName: "pages/dashboard/settings/menu" */))
const _3fdc2eaa = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\permissions.vue' /* webpackChunkName: "pages/dashboard/settings/permissions" */))
const _78c955a4 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\roles.vue' /* webpackChunkName: "pages/dashboard/settings/roles" */))
const _7ed1bd38 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\setup.vue' /* webpackChunkName: "pages/dashboard/settings/setup" */))
const _f9e6b816 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tasks\\show.vue' /* webpackChunkName: "pages/dashboard/tasks/show" */))
const _0a06c677 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\users\\current.vue' /* webpackChunkName: "pages/dashboard/users/current" */))
const _4ab246c4 = () => interopDefault(import('..\\nuxt\\pages\\index.vue' /* webpackChunkName: "pages/index" */))

const emptyFn = () => {}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: '/',
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/about-us",
    component: _350f25dc,
    name: "about-us"
  }, {
    path: "/booking",
    component: _6a415c4b,
    name: "booking"
  }, {
    path: "/category",
    component: _ee4ff408,
    name: "category"
  }, {
    path: "/contact-us",
    component: _30ae2869,
    name: "contact-us"
  }, {
    path: "/dashboard",
    component: _cfb61188,
    name: "dashboard"
  }, {
    path: "/faq",
    component: _0e473588,
    name: "faq"
  }, {
    path: "/item",
    component: _639356de,
    name: "item"
  }, {
    path: "/item-detail",
    component: _769b8a3d,
    name: "item-detail"
  }, {
    path: "/privacy-policy",
    component: _07add315,
    name: "privacy-policy"
  }, {
    path: "/terms",
    component: _169ff60e,
    name: "terms"
  }, {
    path: "/auth/login",
    component: _547f2c33,
    name: "auth-login"
  }, {
    path: "/dashboard/posts",
    component: _2e6717b6,
    name: "dashboard-posts"
  }, {
    path: "/dashboard/tasks",
    component: _2278e18a,
    name: "dashboard-tasks"
  }, {
    path: "/dashboard/users",
    component: _fe274260,
    name: "dashboard-users"
  }, {
    path: "/student/announcement",
    component: _efc46c98,
    name: "student-announcement"
  }, {
    path: "/student/print",
    component: _2f0aa710,
    name: "student-print"
  }, {
    path: "/student/register",
    component: _2758e0e0,
    name: "student-register"
  }, {
    path: "/dashboard/financial/account",
    component: _1a73bd4a,
    name: "dashboard-financial-account"
  }, {
    path: "/dashboard/inventory/contact",
    component: _441197ca,
    name: "dashboard-inventory-contact"
  }, {
    path: "/dashboard/inventory/item",
    component: _d8ecf74e,
    name: "dashboard-inventory-item"
  }, {
    path: "/dashboard/posts/category",
    component: _111ed0fb,
    name: "dashboard-posts-category"
  }, {
    path: "/dashboard/purchase/invoice",
    component: _1fb56a36,
    name: "dashboard-purchase-invoice"
  }, {
    path: "/dashboard/purchase/order",
    component: _fe6c83d2,
    name: "dashboard-purchase-order"
  }, {
    path: "/dashboard/purchase/payment",
    component: _117376e2,
    name: "dashboard-purchase-payment"
  }, {
    path: "/dashboard/purchase/quote",
    component: _3fbc2e45,
    name: "dashboard-purchase-quote"
  }, {
    path: "/dashboard/purchase/receipt",
    component: _6765c87e,
    name: "dashboard-purchase-receipt"
  }, {
    path: "/dashboard/purchase/return",
    component: _84a73a92,
    name: "dashboard-purchase-return"
  }, {
    path: "/dashboard/sales/delivery",
    component: _5d307a6a,
    name: "dashboard-sales-delivery"
  }, {
    path: "/dashboard/sales/invoice",
    component: _18929da7,
    name: "dashboard-sales-invoice"
  }, {
    path: "/dashboard/sales/order",
    component: _d4e1cc70,
    name: "dashboard-sales-order"
  }, {
    path: "/dashboard/sales/payment",
    component: _1fb91000,
    name: "dashboard-sales-payment"
  }, {
    path: "/dashboard/sales/quote",
    component: _548189f6,
    name: "dashboard-sales-quote"
  }, {
    path: "/dashboard/sales/return",
    component: _41927d26,
    name: "dashboard-sales-return"
  }, {
    path: "/dashboard/settings/menu",
    component: _101fa070,
    name: "dashboard-settings-menu"
  }, {
    path: "/dashboard/settings/permissions",
    component: _3fdc2eaa,
    name: "dashboard-settings-permissions"
  }, {
    path: "/dashboard/settings/roles",
    component: _78c955a4,
    name: "dashboard-settings-roles"
  }, {
    path: "/dashboard/settings/setup",
    component: _7ed1bd38,
    name: "dashboard-settings-setup"
  }, {
    path: "/dashboard/tasks/show",
    component: _f9e6b816,
    name: "dashboard-tasks-show"
  }, {
    path: "/dashboard/users/current",
    component: _0a06c677,
    name: "dashboard-users-current"
  }, {
    path: "/",
    component: _4ab246c4,
    name: "index"
  }, {
    path: "/__nuxt_laravel",
    component: _4ab246c4,
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
