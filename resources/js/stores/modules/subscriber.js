import { mutations, actions, getters } from "@stores/global"

const initialState = () => ({
    initURL: "/app/subscribers",
    formErrors: {},
})

const subscriber = {
    namespaced: true,
    state: initialState,
    modules: {},
    mutations: {
        ...mutations,
    },
    actions: {
        ...actions,
    },
    getters: {
        ...getters,
    },
}

export default subscriber
