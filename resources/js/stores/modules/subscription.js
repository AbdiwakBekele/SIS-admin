import { mutations, actions, getters } from "@stores/global"

const initialState = () => ({
    initURL: "/app/subscriptions",
    formErrors: {},
})

const subscription = {
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

export default subscription
