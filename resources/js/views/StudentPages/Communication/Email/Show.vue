<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            {
                label: $trans('communication.communication'),
                path: 'Communication',
            },
            {
                label: $trans('communication.email.email'),
                path: 'CommunicationEmail',
            },
        ]"
    >
        <PageHeaderAction
            name="CommunicationEmail"
            :title="$trans('communication.email.email')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            :module-uuid="route.params.muuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'CommunicationEmail', params: { uuid: email.uuid } })"
        >
            <template #title>
                #{{ email.subject_excerpt }}
            </template>
            <template #audience>
                <div v-for="type in email.audience_types" :key="type">{{ type }}</div>
            </template>
            <template #recipients>
                {{ email.recipient_count }}
            </template>
            <template #created>
                {{ email.created_at.formatted }}
            </template>
            <template #updated>
                {{ email.updated_at.formatted }}
            </template>
            <template #inclusion>
                <div v-for="inc in email.inclusion_list" :key="inc">{{ inc }}</div>
            </template>
            <template #exclusion>
                <div v-for="exc in email.exclusion_list" :key="exc">{{ exc }}</div>
            </template>
            <template #media>
                <div v-for="file in email.media" :key="file.uuid">{{ file.file_name }}</div>
            </template>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "CommunicationEmailShow",
}
</script>

<script setup>
import { reactive } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"

const store = useStore()
const route = useRoute()
const router = useRouter()

const initialEmail = {}

const initUrl = "app/communication/emails"

const email = reactive({ ...initialEmail })

const setItem = (data) => {
    Object.assign(email, data)
}

const filteredAudience = (type) => {
    return email.audiences.filter((audience) => {
        return audience.type === type
    })
}
</script>




