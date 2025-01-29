import dashboard from "./dashboard"
import user from "./user"
import chat from "./chat"
import customField from "./customField"
import utility from "./utility"
import config from "./config"
import product from "./product"

import plan from "./plan"
import tenant from "./tenant"
import subscription from "./subscription"
import subscriber from "./subscriber"
import query from "./query"

export default [
    ...dashboard,
    ...tenant,
    ...subscription,
    ...plan,
    ...subscriber,
    ...query,
    ...user,
    ...chat,
    ...customField,
    ...utility,
    ...config,
    ...product,
]
