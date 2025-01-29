export default [
    {
        path: "plans",
        name: "Plan",
        redirect: { name: "PlanList" },
        meta: {
            label: "plan.plan",
            icon: "fas fa-boxes",
            hideChildren: true,
            permissions: ["plan:read"],
        },
        component: {
            template: "<router-view></router-view>",
        },
        children: [
            {
                path: "",
                name: "PlanList",
                meta: {
                    trans: "global.list",
                    label: "plan.plans",
                },
                component: () => import("@views/Pages/Plan/Index.vue"),
            },
            {
                path: "create",
                name: "PlanCreate",
                meta: {
                    type: "create",
                    action: "create",
                    trans: "global.add",
                    label: "plan.plan",
                    permissions: ["plan:create"],
                },
                component: () => import("@views/Pages/Plan/Action.vue"),
            },
            {
                path: ":uuid/edit",
                name: "PlanEdit",
                meta: {
                    type: "edit",
                    action: "update",
                    trans: "global.edit",
                    label: "plan.plan",
                    permissions: ["plan:edit"],
                },
                component: () => import("@views/Pages/Plan/Action.vue"),
            },
            {
                path: ":uuid/duplicate",
                name: "PlanDuplicate",
                meta: {
                    type: "duplicate",
                    action: "create",
                    trans: "global.duplicate",
                    label: "plan.plan",
                    permissions: ["plan:create"],
                },
                component: () => import("@views/Pages/Plan/Action.vue"),
            },
            {
                path: ":uuid",
                name: "PlanShow",
                meta: {
                    trans: "global.detail",
                    label: "plan.plan",
                },
                component: () => import("@views/Pages/Plan/Show.vue"),
            },
        ],
    },
]
