export default [
    {
        path: "tenants",
        name: "Tenant",
        redirect: { name: "TenantList" },
        meta: {
            label: "tenant.tenant",
            icon: "fas fa-user-group",
            hideChildren: true,
            permissions: ["tenant:read"],
        },
        component: {
            template: "<router-view></router-view>",
        },
        children: [
            {
                path: "config",
                name: "TenantConfig",
                redirect: { name: "TenantConfigGeneral" },
                meta: {
                    isNotNav: true,
                    type: "config",
                    icon: "fas fa-cog",
                    action: "config",
                    trans: "global.config",
                    label: "config.config",
                    permissions: ["tenant:config"],
                },
                component: () => import("@views/Pages/Tenant/Config/Index.vue"),
                children: [
                    {
                        path: "general",
                        name: "TenantConfigGeneral",
                        meta: {
                            type: "config",
                            action: "config",
                            trans: "config.config",
                            label: "config.config",
                        },
                        component: () =>
                            import("@views/Pages/Tenant/Config/General.vue"),
                    },
                ],
            },
            {
                path: "",
                name: "TenantList",
                meta: {
                    trans: "global.list",
                    label: "tenant.tenants",
                },
                component: () => import("@views/Pages/Tenant/Index.vue"),
            },
            {
                path: "create",
                name: "TenantCreate",
                meta: {
                    type: "create",
                    action: "create",
                    trans: "global.add",
                    label: "tenant.tenant",
                    permissions: ["tenant:create"],
                },
                component: () => import("@views/Pages/Tenant/Action.vue"),
            },
            {
                path: ":uuid/edit",
                name: "TenantEdit",
                meta: {
                    type: "edit",
                    action: "update",
                    trans: "global.edit",
                    label: "tenant.tenant",
                    permissions: ["tenant:edit"],
                },
                component: () => import("@views/Pages/Tenant/Action.vue"),
            },
            {
                path: ":uuid/duplicate",
                name: "TenantDuplicate",
                meta: {
                    type: "duplicate",
                    action: "create",
                    trans: "global.duplicate",
                    label: "tenant.tenant",
                    permissions: ["tenant:create"],
                },
                component: () => import("@views/Pages/Tenant/Action.vue"),
            },
            {
                path: ":uuid",
                name: "TenantShow",
                meta: {
                    trans: "global.detail",
                    label: "tenant.tenant",
                },
                component: () => import("@views/Pages/Tenant/Show.vue"),
            },
        ],
    },
]
