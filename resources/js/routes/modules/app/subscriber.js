export default [
    {
        path: "subscribers",
        name: "Subscriber",
        redirect: { name: "SubscriberList" },
        meta: {
            label: "subscriber.subscriber",
            icon: "fas fa-rss",
            hideChildren: true,
            permissions: ["subscriber:read"],
        },
        component: {
            template: "<router-view></router-view>",
        },
        children: [
            {
                path: "",
                name: "SubscriberList",
                meta: {
                    trans: "global.list",
                    label: "subscriber.subscribers",
                },
                component: () => import("@views/Pages/Subscriber/Index.vue"),
            },
            {
                path: "create",
                name: "SubscriberCreate",
                meta: {
                    type: "create",
                    action: "create",
                    trans: "global.add",
                    label: "subscriber.subscriber",
                    permissions: ["subscriber:create"],
                },
                component: () => import("@views/Pages/Subscriber/Action.vue"),
            },
            {
                path: ":uuid/edit",
                name: "SubscriberEdit",
                meta: {
                    type: "edit",
                    action: "update",
                    trans: "global.edit",
                    label: "subscriber.subscriber",
                    permissions: ["subscriber:edit"],
                },
                component: () => import("@views/Pages/Subscriber/Action.vue"),
            },
            {
                path: ":uuid/duplicate",
                name: "SubscriberDuplicate",
                meta: {
                    type: "duplicate",
                    action: "create",
                    trans: "global.duplicate",
                    label: "subscriber.subscriber",
                    permissions: ["subscriber:create"],
                },
                component: () => import("@views/Pages/Subscriber/Action.vue"),
            },
            {
                path: ":uuid",
                name: "SubscriberShow",
                meta: {
                    trans: "global.detail",
                    label: "subscriber.subscriber",
                },
                component: () => import("@views/Pages/Subscriber/Show.vue"),
            },
        ],
    },
]
