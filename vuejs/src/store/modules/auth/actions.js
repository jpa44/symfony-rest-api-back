import axios from 'axios';

export const Register = async ({dispatch}, form) => {
    await axios.post('register', form)
    let UserForm = new FormData()
    UserForm.append('username', form.username)
    UserForm.append('password', form.password)
    await dispatch('LogIn', UserForm)
}

export const LogIn = async ({commit}, User) => {
    await axios.post('login', User)
    await commit('setUser', User)
}

export const LogOut = async ({commit}) => {
    let user = null
    commit('logout', user)
}