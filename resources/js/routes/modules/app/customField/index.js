export default [
    {
        path: "custom-fields",
        name: "CustomField",
        redirect: { name: "CustomFieldList" },
        meta: {
            label: "custom_field.custom_field",
            icon: "fas fa-boxes",
            hideChildren: true,
            permissions: ["custom-field:manage"],
            keySearch: true,
        },
        component: {
            template: "<router-view></router-view>",
        },
        children: [
            {
                path: "",
                name: "CustomFieldList",
                meta: {
                    trans: "global.list",
                    label: "custom_field.custom_field",
                },
                component: () => import("@views/Pages/CustomField/Index.vue"),
            },
            {
                path: "create",
                name: "CustomFieldCreate",
                meta: {
                    type: "create",
                    action: "create",
                    trans: "global.add",
                    label: "custom_field.custom_field",
                },
                component: () => import("@views/Pages/CustomField/Action.vue"),
            },
            {
                path: ":uuid/edit",
                name: "CustomFieldEdit",
                meta: {
                    type: "edit",
                    action: "update",
                    trans: "global.edit",
                    label: "custom_field.custom_field",
                },
                component: () => import("@views/Pages/CustomField/Action.vue"),
            },
            {
                path: ":uuid/duplicate",
                name: "CustomFieldDuplicate",
                meta: {
                    type: "duplicate",
                    action: "create",
                    trans: "global.duplicate",
                    label: "custom_field.custom_field",
                },
                component: () => import("@views/Pages/CustomField/Action.vue"),
            },
            {
                path: ":uuid",
                name: "CustomFieldShow",
                meta: {
                    trans: "global.detail",
                    label: "custom_field.custom_field",
                },
                component: () => import("@views/Pages/CustomField/Show.vue"),
            },
        ],
    },
]
