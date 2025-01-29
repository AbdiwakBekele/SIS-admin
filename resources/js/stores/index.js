import { createStore } from "vuex"

import setup from "@stores/modules/setup"
import navigation from "@stores/modules/navigation"
import config from "@stores/modules/config"
import layout from "@stores/modules/layout"
import dashboard from "@stores/modules/dashboard"
import image from "@stores/modules/image"
import auth from "@stores/modules/auth"
import user from "@stores/modules/user"
import chat from "@stores/modules/chat"
import customField from "@stores/modules/customField"
import utility from "@stores/modules/utility"
import option from "@stores/modules/option"
import tag from "@stores/modules/tag"
import moduleImport from "@stores/modules/moduleImport"
import product from "@stores/modules/product"

import plan from "@stores/modules/plan"
import tenant from "@stores/modules/tenant"
import subscription from "@stores/modules/subscription"
import subscriber from "@stores/modules/subscriber"
import query from "@stores/modules/query"

const initialState = () => ({})

const store = createStore({
    modules: {
        setup,
        navigation,
        config,
        layout,
        dashboard,
        image,
        auth,
        user,
        chat,
        customField,
        utility,
        option,
        tag,
        moduleImport,
        product,
        plan,
        tenant,
        subscription,
        subscriber,
        query,
    },
    state: initialState,
    mutations: {},
    actions: {},
    getters: {},
})

export default store
