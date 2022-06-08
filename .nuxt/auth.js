import Middleware from './middleware'
import { Auth, authMiddleware, ExpiredAuthSessionError } from '~auth/runtime'

// Active schemes
import { CookieScheme } from '~auth/runtime'
import { RefreshScheme } from '~auth/runtime'

Middleware.auth = authMiddleware

export default function (ctx, inject) {
  // Options
  const options = {
  "resetOnError": false,
  "ignoreExceptions": false,
  "scopeKey": "scope",
  "rewriteRedirects": true,
  "fullPathRedirect": false,
  "watchLoggedIn": true,
  "redirect": {
    "login": "/auth/login",
    "logout": "/auth/login",
    "home": "/home/business-overview",
    "callback": "/login"
  },
  "vuex": {
    "namespace": "auth"
  },
  "cookie": {
    "prefix": "auth_tizerp_production.",
    "options": {
      "path": "/"
    }
  },
  "localStorage": {
    "prefix": "auth_tizerp_production."
  },
  "defaultStrategy": "laravelSanctum"
}

  // Create a new Auth instance
  const $auth = new Auth(ctx, options)

  // Register strategies
  // laravelSanctum
  $auth.registerStrategy('laravelSanctum', new CookieScheme($auth, {
  "url": "/",
  "endpoints": {
    "csrf": {
      "withCredentials": true,
      "headers": {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      "url": "//sanctum/csrf-cookie"
    },
    "login": {
      "withCredentials": true,
      "headers": {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      "url": "/api/auth/login"
    },
    "logout": {
      "withCredentials": true,
      "headers": {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      "url": "/api/auth/logout"
    },
    "user": {
      "withCredentials": true,
      "headers": {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
        "Accept": "application/json"
      },
      "url": "/api/auth/user"
    }
  },
  "name": "laravelSanctum",
  "cookie": {
    "name": "XSRF-TOKEN"
  },
  "user": {
    "property": false
  }
}))

  // local
  $auth.registerStrategy('local', new RefreshScheme($auth, {
  "token": {
    "property": "token",
    "global": true,
    "maxAge": 28800
  },
  "refreshToken": {
    "property": "token",
    "data": "token",
    "maxAge": 2592000
  },
  "user": {
    "property": "user",
    "autoFetch": true
  },
  "endpoints": {
    "login": {
      "url": "/api/auth/login",
      "method": "post"
    },
    "logout": {
      "url": "/api/auth/logout",
      "method": "post"
    },
    "user": {
      "url": "/api/auth/user",
      "method": "get"
    },
    "refresh": {
      "url": "/api/auth/refresh",
      "method": "post"
    }
  },
  "name": "local"
}))

  // Inject it to nuxt context as $auth
  inject('auth', $auth)
  ctx.$auth = $auth

  // Initialize auth
  return $auth.init().catch(error => {
    if (process.client) {
      // Don't console log expired auth session errors. This error is common, and expected to happen.
      // The error happens whenever the user does an ssr request (reload/initial navigation) with an expired refresh
      // token. We don't want to log this as an error.
      if (error instanceof ExpiredAuthSessionError) {
        return
      }

      console.error('[ERROR] [AUTH]', error)
    }
  })
}
