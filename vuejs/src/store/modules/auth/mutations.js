export default {
    setUser(state, username) {
        state.user = username
    },
    LogOut(state) {
        state.user = null
    },
}