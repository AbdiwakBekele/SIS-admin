import { trans } from "@core/helpers/trans"
import { getOptionRoutes } from "../option"
import store from "@stores"

function getConfig(key) {
    return store.getters["config/config"](key)
}

export default [
    {
        path: "chat",
        name: "Chat",
        meta: {
            isNotNav: true,
            label: "chat.chat",
            icon: "fas fa-message",
            permissions: ["chat:access"],
        },
        beforeEnter: (to, from, next) => {
            if (getConfig("chat.enableChat")) {
                next()
            } else {
                next("/")
            }
        },
        component: () => import("@views/Pages/Chat/Index.vue"),
        children: [
            {
                path: ":uuid",
                name: "ChatMessage",
                meta: {
                    trans: "global.show",
                    label: "chat.chat",
                },
                component: () => import("@views/Pages/Chat/Message.vue"),
            },
        ],
    },
]
