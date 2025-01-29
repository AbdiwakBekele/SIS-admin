import { mutations, actions, getters } from "@stores/global"

const initialState = () => ({
    initURL: "/app/custom-fields",
    formErrors: {},
})

const customField = {
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
export default customField
