import mailTemplate from "./mailTemplate"
import smsTemplate from "./smsTemplate"
import pushNotificationTemplate from "./pushNotificationTemplate"
import moduleList from "./module"
import locale from "./locale"
import role from "./role"
import permission from "./permission"

export default [
    {
        path: "config",
        name: "Config",
        redirect: { name: "ConfigGeneral" },
        meta: {
            label: "config.config",
            icon: "fas fa-cog",
            permissions: ["config:store"],
            isHiddenNav: false,
            hideChildren: false,
        },
        component: {
            template: "<router-view></router-view>",
        },
        children: [
            {
                path: "general",
                name: "ConfigGeneral",
                meta: {
                    label: "config.general.general",
                    icon: "far fa-building",
                    key: "general",
                },
                component: () =>
                    import("@views/Pages/Config/General/Index.vue"),
            },
            {
                path: "asset",
                name: "ConfigAsset",
                meta: {
                    label: "config.asset.asset",
                    icon: "far fa-images",
                    key: "asset",
                },
                component: () => import("@views/Pages/Config/Asset/Index.vue"),
            },
            {
                path: "system",
                name: "ConfigSystem",
                meta: {
                    label: "config.system.system",
                    icon: "fas fa-cogs",
                    key: "system",
                },
                component: () => import("@views/Pages/Config/System/Index.vue"),
            },
            {
                path: "auth",
                name: "ConfigAuth",
                meta: {
                    label: "config.auth.auth",
                    icon: "fas fa-sign-in-alt",
                    key: "auth",
                },
                component: () => import("@views/Pages/Config/Auth/Index.vue"),
            },
            {
                path: "mail",
                name: "ConfigMail",
                meta: {
                    label: "config.mail.mail",
                    icon: "fas fa-envelope",
                    key: "mail",
                },
                component: () => import("@views/Pages/Config/Mail/Index.vue"),
            },
            {
                path: "sms",
                name: "ConfigSMS",
                meta: {
                    label: "config.sms.sms",
                    icon: "fas fa-message",
                    key: "sms",
                },
                component: () => import("@views/Pages/Config/SMS/Index.vue"),
            },
            {
                path: "notification",
                name: "ConfigNotification",
                meta: {
                    label: "config.notification.notification",
                    icon: "fas fa-bell",
                    key: "notification",
                },
                component: () =>
                    import("@views/Pages/Config/Notification/Index.vue"),
            },
            {
                path: "chat",
                name: "ConfigChat",
                meta: {
                    label: "config.chat.chat",
                    icon: "fas fa-comment",
                    key: "chat",
                },
                component: () => import("@views/Pages/Config/Chat/Index.vue"),
            },
            ...mailTemplate,
            ...smsTemplate,
            ...pushNotificationTemplate,
            {
                path: "site",
                name: "ConfigSite",
                meta: {
                    label: "config.site.site",
                    icon: "fas fa-globe",
                    key: "site",
                },
                component: () => import("@views/Pages/Config/Site/Index.vue"),
            },
            {
                path: "server",
                name: "ConfigServer",
                meta: {
                    label: "config.server.server",
                    icon: "fas fa-server",
                    key: "server",
                },
                component: () => import("@views/Pages/Config/Server/Index.vue"),
            },
            {
                path: "feature",
                name: "ConfigFeature",
                meta: {
                    label: "config.feature.feature",
                    icon: "fas fa-list",
                    key: "feature",
                },
                component: () =>
                    import("@views/Pages/Config/Feature/Index.vue"),
            },
            ...moduleList,
            {
                path: "social-network",
                name: "ConfigSocialNetwork",
                meta: {
                    label: "config.social.social",
                    icon: "fas fa-share-alt",
                    key: "social-network",
                },
                component: () =>
                    import("@views/Pages/Config/SocialNetwork/Index.vue"),
            },
            ...role,
            ...permission,
            ...locale,
        ],
    },
]
