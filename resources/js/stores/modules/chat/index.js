import axios from "axios"
import * as Api from "@core/apis"
import * as Form from "@core/utils/form"
import { useToast } from "vue-toastification"
import { toQueryString } from "@core/helpers/array"
import { mutations, actions, getters } from "@stores/global"

const toast = useToast()

const initialState = () => ({
    initURL: "/app/",
    formErrors: {},
})

const chat = {
    namespaced: true,
    state: initialState,
    mutations: {
        ...mutations,
    },
    actions: {
        resetFormErrors: actions.resetFormErrors,

        async getChats({ state, commit }, payload) {
            try {
                let url = state.initURL + "chat"
                const response = await Api.custom({
                    url: toQueryString(url, payload),
                    method: "GET",
                })
                return response
            } catch (error) {
                Form.getErrors(error)
                throw error
            }
        },

        async getChat({ state, commit }, uuid) {
            try {
                const response = await Api.custom({
                    url: `${state.initURL}chat/${uuid}`,
                    method: "GET",
                })
                return response
            } catch (error) {
                Form.getErrors(error)
                throw error
            }
        },

        async createChat({ state, commit }, payload) {
            try {
                const response = await Api.custom({
                    url: state.initURL + "chat",
                    method: "POST",
                    data: payload,
                })
                return response
            } catch (error) {
                Form.getErrors(error)
                throw error
            }
        },

        async getMessages({ state, commit }, payload) {
            try {
                const response = await Api.custom({
                    url: `${state.initURL}chat/${payload.uuid}/messages?cursor=${payload.cursor}`,
                    method: "GET",
                })
                return response
            } catch (error) {
                Form.getErrors(error)
                throw error
            }
        },

        async sendMessage({ state, commit }, { uuid, message }) {
            try {
                const response = await Api.custom({
                    url: `${state.initURL}chat/${uuid}/messages`,
                    method: "POST",
                    data: message,
                })
                return response
            } catch (error) {
                Form.getErrors(error)
                throw error
            }
        },

        async markAsRead({ state, commit }, uuid) {
            try {
                const response = await Api.custom({
                    url: `${state.initURL}chat/${uuid}/read`,
                    method: "POST",
                })
                return response
            } catch (error) {
                throw error
            }
        },

        async deleteChat({ state, commit }, uuid) {
            try {
                const response = await Api.custom({
                    url: `${state.initURL}chat/${uuid}`,
                    method: "DELETE",
                })
                return response
            } catch (error) {
                throw error
            }
        },

        async searchUsers({ state, commit }, query) {
            try {
                const response = await Api.custom({
                    url: `${state.initURL}chat/users`,
                    method: "GET",
                    params: { query },
                })
                return response
            } catch (error) {
                throw error
            }
        },
    },
    getters: {
        ...getters,
    },
}

export default chat
