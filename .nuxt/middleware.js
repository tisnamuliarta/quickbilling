const middleware = {}

middleware['guest'] = require('..\\nuxt\\middleware\\guest.js')
middleware['guest'] = middleware['guest'].default || middleware['guest']

middleware['verification'] = require('..\\nuxt\\middleware\\verification.js')
middleware['verification'] = middleware['verification'].default || middleware['verification']

export default middleware
