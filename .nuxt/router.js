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
const _5122046c = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\order\\index.vue' /* webpackChunkName: "pages/dashboard/order/index" */))
const _2e6717b6 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\posts\\index.vue' /* webpackChunkName: "pages/dashboard/posts/index" */))
const _2278e18a = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tasks\\index.vue' /* webpackChunkName: "pages/dashboard/tasks/index" */))
const _fe274260 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\users\\index.vue' /* webpackChunkName: "pages/dashboard/users/index" */))
const _efc46c98 = () => interopDefault(import('..\\nuxt\\pages\\student\\announcement.vue' /* webpackChunkName: "pages/student/announcement" */))
const _2f0aa710 = () => interopDefault(import('..\\nuxt\\pages\\student\\print.vue' /* webpackChunkName: "pages/student/print" */))
const _2758e0e0 = () => interopDefault(import('..\\nuxt\\pages\\student\\register.vue' /* webpackChunkName: "pages/student/register" */))
const _1a73bd4a = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\financial\\account.vue' /* webpackChunkName: "pages/dashboard/financial/account" */))
const _63dfce18 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\inventory\\products\\index.vue' /* webpackChunkName: "pages/dashboard/inventory/products/index" */))
const _14fc2c0d = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\order\\person.vue' /* webpackChunkName: "pages/dashboard/order/person" */))
const _b203eb80 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\order\\special-offer.vue' /* webpackChunkName: "pages/dashboard/order/special-offer" */))
const _095e6bac = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\order\\status.vue' /* webpackChunkName: "pages/dashboard/order/status" */))
const _111ed0fb = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\posts\\category.vue' /* webpackChunkName: "pages/dashboard/posts/category" */))
const _101fa070 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\menu.vue' /* webpackChunkName: "pages/dashboard/settings/menu" */))
const _3fdc2eaa = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\permissions.vue' /* webpackChunkName: "pages/dashboard/settings/permissions" */))
const _78c955a4 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\roles.vue' /* webpackChunkName: "pages/dashboard/settings/roles" */))
const _7ed1bd38 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\settings\\setup.vue' /* webpackChunkName: "pages/dashboard/settings/setup" */))
const _f9e6b816 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\tasks\\show.vue' /* webpackChunkName: "pages/dashboard/tasks/show" */))
const _0a06c677 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\users\\current.vue' /* webpackChunkName: "pages/dashboard/users/current" */))
const _65148e4d = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\inventory\\products\\brand.vue' /* webpackChunkName: "pages/dashboard/inventory/products/brand" */))
const _fbe163b0 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\inventory\\products\\category.vue' /* webpackChunkName: "pages/dashboard/inventory/products/category" */))
const _bcc0ed68 = () => interopDefault(import('..\\nuxt\\pages\\dashboard\\inventory\\products\\price-list.vue' /* webpackChunkName: "pages/dashboard/inventory/products/price-list" */))
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
    path: "/dashboard/order",
    component: _5122046c,
    name: "dashboard-order"
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
    path: "/dashboard/inventory/products",
    component: _63dfce18,
    name: "dashboard-inventory-products"
  }, {
    path: "/dashboard/order/person",
    component: _14fc2c0d,
    name: "dashboard-order-person"
  }, {
    path: "/dashboard/order/special-offer",
    component: _b203eb80,
    name: "dashboard-order-special-offer"
  }, {
    path: "/dashboard/order/status",
    component: _095e6bac,
    name: "dashboard-order-status"
  }, {
    path: "/dashboard/posts/category",
    component: _111ed0fb,
    name: "dashboard-posts-category"
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
    path: "/dashboard/inventory/products/brand",
    component: _65148e4d,
    name: "dashboard-inventory-products-brand"
  }, {
    path: "/dashboard/inventory/products/category",
    component: _fbe163b0,
    name: "dashboard-inventory-products-category"
  }, {
    path: "/dashboard/inventory/products/price-list",
    component: _bcc0ed68,
    name: "dashboard-inventory-products-price-list"
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
