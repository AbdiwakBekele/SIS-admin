export default [
    {
        path: "sms-template",
        name: "ConfigSMSTemplate",
        redirect: { name: "ConfigSMSTemplateList" },
        meta: {
            label: "config.sms.template.template",
            icon: "fas fa-file-lines",
            hideChildren: true,
            key: "smsTemplate",
        },
        component: {
            template: "<router-view></router-view>",
        },
        children: [
            {
                path: "",
                name: "ConfigSMSTemplateList",
                meta: {
                    trans: "global.list",
                    label: "config.sms.template.template",
                },
                component: () =>
                    import("@views/Pages/Config/SMSTemplate/Index.vue"),
            },
            {
                path: ":uuid/edit",
                name: "ConfigSMSTemplateEdit",
                meta: {
                    type: "edit",
                    action: "update",
                    trans: "global.edit",
                    label: "config.sms.template.template",
                },
                component: () =>
                    import("@views/Pages/Config/SMSTemplate/Action.vue"),
            },
            // {
            //     path: ":uuid",
            //     name: "ConfigSMSTemplateShow",
            //     meta: {
            //         trans: "global.detail",
            //         label: "config.sms.template.template",
            //     },
            //     component: () =>
            //         import("@views/Pages/Config/SMSTemplate/Show.vue"),
            // },
        ],
    },
]
