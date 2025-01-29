export default [
    {
        path: "queries",
        name: "Query",
        redirect: { name: "QueryList" },
        meta: {
            label: "query.query",
            icon: "fas fa-envelope",
            hideChildren: true,
            permissions: ["query:read"],
        },
        component: {
            template: "<router-view></router-view>",
        },
        children: [
            {
                path: "",
                name: "QueryList",
                meta: {
                    trans: "global.list",
                    label: "query.queries",
                },
                component: () => import("@views/Pages/Query/Index.vue"),
            },
            {
                path: ":uuid",
                name: "QueryShow",
                meta: {
                    trans: "global.detail",
                    label: "query.query",
                },
                component: () => import("@views/Pages/Query/Show.vue"),
            },
        ],
    },
]
