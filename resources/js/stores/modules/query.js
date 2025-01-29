import { mutations, actions, getters } from "@stores/global"

const initialState = () => ({
    initURL: "/app/queries",
    formErrors: {},
})

const query = {
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

export default query
