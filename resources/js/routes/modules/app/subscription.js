export default [
    {
        path: "subscriptions",
        name: "Subscription",
        redirect: { name: "SubscriptionList" },
        meta: {
            label: "subscription.subscription",
            icon: "fas fa-credit-card",
            hideChildren: true,
            permissions: ["subscription:read"],
        },
        component: {
            template: "<router-view></router-view>",
        },
        children: [
            {
                path: "config",
                name: "SubscriptionConfig",
                redirect: { name: "SubscriptionConfigGeneral" },
                meta: {
                    isNotNav: true,
                    type: "config",
                    icon: "fas fa-cog",
                    action: "config",
                    trans: "global.config",
                    label: "config.config",
                    permissions: ["subscription:config"],
                },
                component: () =>
                    import("@views/Pages/Subscription/Config/Index.vue"),
                children: [
                    {
                        path: "general",
                        name: "SubscriptionConfigGeneral",
                        meta: {
                            type: "config",
                            action: "config",
                            trans: "config.config",
                            label: "config.config",
                        },
                        component: () =>
                            import(
                                "@views/Pages/Subscription/Config/General.vue"
                            ),
                    },
                ],
            },
            {
                path: "",
                name: "SubscriptionList",
                meta: {
                    trans: "global.list",
                    label: "subscription.subscriptions",
                },
                component: () => import("@views/Pages/Subscription/Index.vue"),
            },
            {
                path: "create",
                name: "SubscriptionCreate",
                meta: {
                    type: "create",
                    action: "create",
                    trans: "global.add",
                    label: "subscription.subscription",
                    permissions: ["subscription:create"],
                },
                component: () => import("@views/Pages/Subscription/Action.vue"),
            },
            {
                path: ":uuid/edit",
                name: "SubscriptionEdit",
                meta: {
                    type: "edit",
                    action: "update",
                    trans: "global.edit",
                    label: "subscription.subscription",
                    permissions: ["subscription:edit"],
                },
                component: () => import("@views/Pages/Subscription/Action.vue"),
            },
            {
                path: ":uuid/duplicate",
                name: "SubscriptionDuplicate",
                meta: {
                    type: "duplicate",
                    action: "create",
                    trans: "global.duplicate",
                    label: "subscription.subscription",
                    permissions: ["subscription:create"],
                },
                component: () => import("@views/Pages/Subscription/Action.vue"),
            },
            {
                path: ":uuid",
                name: "SubscriptionShow",
                meta: {
                    trans: "global.detail",
                    label: "subscription.subscription",
                },
                component: () => import("@views/Pages/Subscription/Show.vue"),
            },
        ],
    },
]
