export default [
    {
        path: "push-notification-template",
        name: "ConfigPushNotificationTemplate",
        redirect: { name: "ConfigPushNotificationTemplateList" },
        meta: {
            label: "config.push_notification.template.template",
            icon: "fas fa-file-lines",
            hideChildren: true,
            key: "pushNotificationTemplate",
        },
        component: {
            template: "<router-view></router-view>",
        },
        children: [
            {
                path: "",
                name: "ConfigPushNotificationTemplateList",
                meta: {
                    trans: "global.list",
                    label: "config.push_notification.template.template",
                },
                component: () =>
                    import(
                        "@views/Pages/Config/PushNotificationTemplate/Index.vue"
                    ),
            },
            {
                path: ":uuid/edit",
                name: "ConfigPushNotificationTemplateEdit",
                meta: {
                    type: "edit",
                    action: "update",
                    trans: "global.edit",
                    label: "config.push_notification.template.template",
                },
                component: () =>
                    import(
                        "@views/Pages/Config/PushNotificationTemplate/Action.vue"
                    ),
            },
            // {
            //     path: ":uuid",
            //     name: "ConfigPushNotificationTemplateShow",
            //     meta: {
            //         trans: "global.detail",
            //         label: "config.push_notification.template.template",
            //     },
            //     component: () =>
            //         import(
            //             "@views/Pages/Config/PushNotificationTemplate/Show.vue"
            //         ),
            // },
        ],
    },
]
