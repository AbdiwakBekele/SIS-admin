export default [
    {
        path: "module",
        name: "ConfigModule",
        meta: {
            label: "config.module.module",
            icon: "fas fa-boxes-stacked",
            hideChildren: true,
            key: "module",
        },
        component: () => import("@views/Pages/Config/Module/Index.vue"),
    },
]
