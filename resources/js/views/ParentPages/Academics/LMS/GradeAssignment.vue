<template>
    <PageHeader :title="`Grade Assignment`" :navs="[]" />

    <ParentTransition appear :visibility="true">
        <div class="grid grid-cols-3 gap-6 px-2 py-1">
            <!-- Left: Score Requested Form -->
            <div class="col-span-2 space-y-4">
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4">Score Requested</h3>
                    <p class="text-sm text-gray-600 mb-4">Submitted: {{ submittedAt }}</p>

                    <!-- Student Comment / Score Options -->
                    <div class="mb-4 grid grid-cols-3">
                        <div class="col-span-1">
                            <label class="inline-flex items-center mb-3">
                                <input type="radio" class="form-radio" value="score" v-model="mode" />
                                <span class="ml-2">Score Activity</span>
                            </label>

                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio" value="review" v-model="mode" />
                                <span class="ml-2">Review/Return without score</span>
                            </label>
                        </div>

                        <!-- Score Input -->
                        <span class="col-span-1 mb-4 w-25">
                            <BaseInput type="number" v-model="form.score" name="score" label="Score (0–100)" :min="0"
                                :max="100" :disabled="mode !== 'score'" />
                        </span>
                    </div>

                    <!-- Student Comment Editor -->
                    <div>
                        <BaseEditor v-model="form.comment" name="comment" :label="`Enter Comments`" />
                    </div>

                    <div class="m-1 text-right">
                        <BaseButton>Save</BaseButton>
                    </div>

                </div>
            </div>

            <!-- Right: History & Upload -->
            <div class="col-span-1 space-y-4">
                <!-- History and Comments -->
                <div class="bg-white rounded shadow p-4">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4">History and Comments</h3>
                    <ul class="space-y-3 max-h-80 overflow-y-auto">
                        <li v-for="(h, i) in history" :key="i" class="flex items-start space-x-3">
                            <i class="fa-solid fa-check-circle text-green-500 mt-1"></i>
                            <div>
                                <p class="text-sm"><strong>{{ h.title }}</strong></p>
                                <p class="text-xs text-gray-500">{{ h.when }}</p>
                                <p class="text-sm">{{ h.body }}</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Upload Commented File -->
                <div class="bg-white rounded shadow p-4">
                    <h3 class="text-lg font-semibold border-b pb-2 mb-4">Upload Commented File</h3>
                    <MediaUpload multiple module="assignments" :media="form.uploads" :media-token="form.mediaToken"
                        @isUpdated="form.uploadsUpdated = true" @setHash="(hash) => form.uploadHashes.push(hash)" />
                </div>
            </div>
        </div>
    </ParentTransition>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { v4 as uuidv4 } from 'uuid'

const submittedAt = '5/16/2025 3:25 PM'

const form = reactive({
    score: null,
    comment: '',
    uploads: [],
    uploadsUpdated: false,
    mediaToken: uuidv4(),
    uploadHashes: [],
})

const mode = ref('review') // 'score' or 'review'

const history = [
    {
        title: 'Score Activity: Enzymes',
        when: '5/14/2025 10:12 AM',
        body: 'Initial grading completed.'
    },
    {
        title: 'Review Requested',
        when: '5/15/2025 2:37 PM',
        body: 'Please double-check section 3.'
    },
    // …more items…
]
</script>
