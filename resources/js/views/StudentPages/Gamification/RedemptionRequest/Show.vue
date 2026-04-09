<template>
    <PageHeader
        :title="$trans('new_enrollment.missing_document_type.props.document_type')"
        :navs="[
            { label: $trans('new_enrollment.new_enrollment'), path: '' },
            { label: $trans('new_enrollment.missing_document_type.props.document_types'), path: 'StudentRedemptionRequestsList' },
        ]"
    >
        <PageHeaderAction
            name="StudentRedemptionRequests"
            :title="$trans('new_enrollment.missing_document_type.props.document_types')"
            :actions="['list']"
        />
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <ShowItem
            :init-url="initUrl"
            :uuid="route.params.uuid"
            @setItem="setItem"
            @redirectTo="router.push({ name: 'StudentRedemptionRequestsList' })"
        >
            <BaseCard v-if="documentType.uuid" class="w-full">
                <template #title>
                    <div class="flex items-center justify-center gap-2">
                        {{ documentType.documentType || 'Unnamed Document Type' }}
                    </div>
                </template>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                    <BaseDataView :label="$trans('new_enrollment.missing_document_type.props.description')">
                        {{ documentType.description || 'N/A' }}
                    </BaseDataView>
                    <BaseDataView :label="$trans('general.created_at')">
                        {{ documentType.createdAt.formatted || 'N/A' }}
                    </BaseDataView>
                </dl>
                <template #footer>
                    <ShowButton>
                        <BaseButton
                            v-if="perform('studentRedemptionRequests:edit')"
                            design="primary"
                            @click="
                                router.push({
                                    name: 'StudentRedemptionRequestsEdit',
                                    params: { uuid: exitInterview.uuid },
                                })
                            "
                        >
                            {{ $trans('general.edit') }}
                        </BaseButton>
                    </ShowButton>
                </template>
            </BaseCard>
            <div v-else class="text-gray-500 p-4 text-center">No exit interview data available</div>
        </ShowItem>
    </ParentTransition>
</template>

<script>
export default {
    name: "StudentRedemptionRequestsShow",
}
</script>

<script setup>
import { reactive, inject } from "vue";
import { useRoute, useRouter } from "vue-router";
import { perform } from "@core/helpers/action";

const route = useRoute();
const router = useRouter();
const $trans = inject("$trans");

const initUrl = "gamification/redemptionRequest/";

const initialDocumentType = {
    uuid: "",
    documentType: "",
    description: "",
    createdAt: null,
    updatedAt: null,
};

const documentType = reactive({ ...initialDocumentType });

const setItem = (data) => {
    Object.assign(documentType, {
        ...initialDocumentType,
        uuid: data.uuid || "",
        documentType: data.documentType || "",
        description: data.description || "",
        createdAt: data.createdAt || null,
        updatedAt: data.updatedAt || null,
    });
};
</script>