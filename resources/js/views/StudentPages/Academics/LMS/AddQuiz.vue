<template>
    <PageHeader :title="`Add Course Quiz`" :navs="[]" />

    <ParentTransition appear :visibility="true">

        <div class="bg-white rounded-lg p-5">
            <div class="grid grid-cols-3 gap-6">
                <!-- Quiz Metadata -->
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" v-model="form.name" name="name" :label="`Title`" autofocus />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" v-model="form.minScore" name="minScore" :label="`Min Score`" />
                </div>
                <div class="col-span-3 sm:col-span-1">
                    <BaseInput type="text" v-model="form.maxScore" name="maxScore" :label="`Max Score`" />
                </div>
                <div class="col-span-3">
                    <BaseEditor v-model="form.description" name="description" :label="`Content`" />
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-lg font-semibold mb-4">Questions</h2>
                <div v-for="(q, idx) in form.questions" :key="idx"
                    class="mb-6 p-5 border border-gray-400 shadow-lg rounded-lg">
                    <div class="flex justify-between border-b border-gray-300 items-center mb-5">
                        <p class="font-medium">Question {{ idx + 1 }}</p>
                        <div class="flex space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" v-model="q.type" value="mcq" class="form-radio" />
                                <span class="ml-2 text-sm">Multiple Choose</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" v-model="q.type" value="tf" class="form-radio" />
                                <span class="ml-2 text-sm">True/False</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" v-model="q.type" value="fill" class="form-radio" />
                                <span class="ml-2 text-sm">Fill in the blank</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" v-model="q.type" value="short" class="form-radio" />
                                <span class="ml-2 text-sm">Short Answer</span>
                            </label>
                        </div>
                        <button @click="removeQuestion(idx)" type="button" class="text-red-500">Remove</button>
                    </div>

                    <!-- Question Prompt -->
                    <div class="mb-4">
                        <label class="block mb-1 text-sm">Prompt</label>
                        <input v-model="q.prompt" type="text" class="border border-gray-300 rounded p-3 w-full"
                            placeholder="Your Question Here..." />
                    </div>

                    <!-- MCQ Options -->
                    <div v-if="q.type === 'mcq'" class="mb-4">
                        <label class="block mb-1 font-medium">Options</label>
                        <div v-for="(opt, oidx) in q.options" :key="oidx" class="flex items-center mb-2 space-x-3">
                            <input v-model="opt.text" type="text"
                                class="border border-gray-300 rounded p-3 flex-1 text-sm" placeholder="Option text" />
                            <label class="inline-flex items-center">
                                <input type="radio" :name="`correct-${idx}`" v-model="q.correctAnswer" :value="oidx"
                                    class="form-radio" />
                                <span class="ml-1">Correct</span>
                            </label>
                            <button @click="removeOption(idx, oidx)" type="button" class="text-red-500">
                                ×
                            </button>
                        </div>
                        <button @click="addOption(idx)" type="button" class="text-blue-600 underline">
                            + Add Option
                        </button>
                    </div>

                    <!-- True/False -->
                    <div v-if="q.type === 'tf'" class="mb-4 flex space-x-6">
                        <label class="inline-flex items-center">
                            <input type="radio" v-model="q.correctAnswer" value="True" class="form-radio" />
                            <span class="ml-2">True</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" v-model="q.correctAnswer" value="False" class="form-radio" />
                            <span class="ml-2">False</span>
                        </label>
                    </div>

                    <!-- Fill in the Blank -->
                    <div v-if="q.type === 'fill'" class="mb-4">
                        <label class="block mb-1 font-medium">Answer</label>
                        <input v-model="q.correctAnswer" type="text" class="border rounded p-2 w-full"
                            placeholder="Enter correct answer" />
                    </div>

                    <!-- Short Answer -->
                    <div v-if="q.type === 'short'" class="mb-4">
                        <label class="block mb-1 font-medium">Answer</label>
                        <textarea v-model="q.correctAnswer" class="border rounded p-2 w-full" rows="3"
                            placeholder="Enter reference answer"></textarea>
                    </div>
                </div>

                <button @click="addQuestion" type="button" class="mt-4 px-4 py-2 bg-gray-600 text-white rounded">
                    + Add Question
                </button>
            </div>

            <div class="m-1 mr-7 text-right">
                <BaseButton>Publish</BaseButton>
                <BaseButton class="mx-2">Save as Draft</BaseButton>
                <BaseButton design="danger">Cancel</BaseButton>
            </div>
        </div>



    </ParentTransition>
</template>

<script setup>
import { reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { cloneDeep } from '@core/utils'
import { getFormErrors } from '@core/helpers/action'

const route = useRoute()
const router = useRouter()

const initForm = {
    name: '',
    minScore: '',
    maxScore: '',
    description: '',
    courseUnit: route.query.uuidUnit,
    questions: []
}

const initUrl = 'schoolSetup/unitTopic/quiz'
const formErrors = getFormErrors(initUrl)
const form = reactive(cloneDeep(initForm))

function addQuestion() {
    form.questions.push({
        type: 'mcq',
        prompt: '',
        options: [{ text: '' }, { text: '' }],
        correctAnswer: null
    })
}

function removeQuestion(idx) {
    form.questions.splice(idx, 1)
}

function addOption(qIdx) {
    form.questions[qIdx].options.push({ text: '' })
}

function removeOption(qIdx, oIdx) {
    form.questions[qIdx].options.splice(oIdx, 1)
}

// initialize with one question
addQuestion()

function setPreRequisites(data) {
    // no prerequisites
}

function setForm(data) {
    Object.assign(initForm, data)
    Object.assign(form, cloneDeep(initForm))
}
</script>

<style scoped>
/* Add any custom styles if needed */
</style>
