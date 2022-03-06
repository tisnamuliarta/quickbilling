export const getters = {
  user(state) {
    return state.auth.user
  },

  isLoggedIn(state) {
    return state.auth.loggedIn
  }
}
