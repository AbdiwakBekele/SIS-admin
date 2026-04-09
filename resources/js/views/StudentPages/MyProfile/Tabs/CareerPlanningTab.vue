<template>
    <!-- Career Planning -->
    <div class="col-span-1 bg-white rounded-lg border-r py-5 text-center mt-2">
        <div class="flex items-end justify-between mb-4 px-4">
            <p class="text-sm text-left font-semibold">Academic Career Planning Info</p>
            <button class="bg-blue-500 px-2 py-1.5 text-white border border-blue-600 rounded-lg shadow-lg hover:bg-blue-600"
                @click="showAcademicCareerPlanningModal = true">
                <i class="fa-solid fa-pen-to-square text-white"></i>
            </button>
        </div>
        <hr class="mx-4 mb-4">

        <!-- Academics Info -->
        <div class="grid grid-cols-12 text-left">
            <!-- Post-secondary choice -->
            <span class="col-span-1 mt-3 ml-4">
                <i class="fa-solid fa-graduation-cap"></i>
            </span>

            <div class="col-span-3 mt-2">
                <p class="text-sm"><strong>Postsecondary Plan</strong></p>
                <div v-if="student.postsecondaryChoices && student.postsecondaryChoices.length">
                    <ul class="list-disc list-inside text-sm text-gray-700">
                        <li v-for="ps in student.postsecondaryChoices" :key="ps.uuid">{{ ps.title }}</li>
                    </ul>
                </div>
                <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                    <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600 font-medium">No Postsecondary Plan</p>
                    <p class="text-xs text-gray-500 mt-1">Postsecondary Plan information is not available at this time.</p>
                </div>
            </div>

            <!-- Career Pathway -->
            <span class="col-span-1 mt-3 ml-4">
                <i class="fa-solid fa-calendar-days"></i>
            </span>

            <div class="col-span-3 mt-2">
                <p class="text-sm"><strong>Career Pathway</strong></p>
                <div v-if="student.careerPathways && student.careerPathways.length > 0">
                    <ul class="list-disc list-inside text-sm text-gray-700"
                        v-for="careerPathway in student.careerPathways" :key="careerPathway.uuid">
                        <li>{{ careerPathway.title }}</li>
                    </ul>
                </div>
                <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                    <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
                    <p class="text-sm text-gray-600 font-medium">No Career Pathway</p>
                    <p class="text-xs text-gray-500 mt-1">Career Pathway information is not available at this time.</p>
                </div>
            </div>
        </div>

        <!-- Academic Career Planning Modal -->
        <BaseModal :visibility="showAcademicCareerPlanningModal" @close="showAcademicCareerPlanningModal = false"
            :title="'Add Academic Career Planning'">
            <div>
                <h2 class="text-lg font-semibold mb-4">Add Academic Career Planning</h2>

                <div class="grid grid-cols-3 gap-6">

                    <!-- PostsecondaryChoice -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSelect multiple v-model="academicCareerPlanningForm.postsecondaryChoice"
                            name="postsecondaryChoice" :label="`Postsecondary Choices`" :options="postsecondaryChoices"
                            label-prop="titles" value-prop="uuid" />

                        <div class="mt-1 flex flex-wrap gap-2"
                            v-if="academicCareerPlanningForm.postsecondaryChoice.length">
                            <span v-for="uuid in academicCareerPlanningForm.postsecondaryChoice" :key="uuid"
                                class="inline-flex items-center bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                {{ psTitle(uuid) }}
                                <button type="button" class="ml-1 hover:text-blue-900" @click.stop="removePs(uuid)"
                                    aria-label="Remove">
                                    ×
                                </button>
                            </span>
                        </div>
                    </div>

                    <!-- CareerPathway -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSelect multiple v-model="academicCareerPlanningForm.careerPathway" name="gradeLevel"
                            :label="`Career Pathways`" :options="careerPathways" label-prop="titles"
                            value-prop="uuid" />

                        <div class="mt-1 flex flex-wrap gap-2" v-if="academicCareerPlanningForm.careerPathway.length">
                            <span v-for="uuid in academicCareerPlanningForm.careerPathway" :key="uuid"
                                class="inline-flex items-center bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full">
                                {{ cpTitle(uuid) }}
                                <button type="button" class="ml-1 hover:text-emerald-900" @click.stop="removeCp(uuid)"
                                    aria-label="Remove">
                                    ×
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-right">
                <BaseButton design="primary" @click="submitAcademicCareerPlanning">
                    Save
                </BaseButton>
            </div>
        </BaseModal>
    </div>

    <!-- Postsecondary Selections -->
    <div class="col-span-1 bg-white rounded-lg border-r py-5 text-center mt-2">
        <div class="flex items-end justify-between mb-4 px-4">
            <p class="text-sm text-left font-semibold">Postsecondary Selections</p>
            <button 
                v-if="!isParent && (postsecondarySelections?.length || 0) < 3"
                class="bg-blue-500 px-2 py-1.5 text-white border border-blue-600 rounded-lg shadow-lg hover:bg-blue-600"
                @click="openPostsecondaryModal()"
                title="Add Postsecondary Selection">
                <i class="fa-solid fa-plus text-white"></i>
            </button>
        </div>
        <hr class="mx-4 mb-4">
        <div v-if="postsecondarySelections && postsecondarySelections.length > 0" class="space-y-4 px-4">
            <div v-for="selection in postsecondarySelections" :key="selection.uuid" 
                class="w-full text-left px-6 py-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow bg-gray-50">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="font-semibold text-base text-gray-900 flex-1">
                        {{ selection.college_name_workplace_military_branch || selection.collegeNameWorkplaceMilitaryBranch || 'N/A' }}
                    </h4>
                    <div v-if="!isParent" class="flex gap-2 ml-4">
                        <button 
                            @click="openPostsecondaryModal(selection)" 
                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors"
                            title="Edit">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button 
                            @click="deletePostsecondarySelection(selection)" 
                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors"
                            title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div v-if="selection.required_gpa || selection.requiredGpa" class="flex flex-col">
                        <span class="text-xs font-semibold text-gray-500 uppercase mb-1">Required GPA for Admission</span>
                        <span class="text-sm text-gray-900">{{ selection.required_gpa || selection.requiredGpa }}</span>
                    </div>
                    <div v-if="selection.required_test_score || selection.requiredTestScore" class="flex flex-col">
                        <span class="text-xs font-semibold text-gray-500 uppercase mb-1">Required Test Score for Admission</span>
                        <span class="text-sm text-gray-900">{{ selection.required_test_score || selection.requiredTestScore }}</span>
                    </div>
                    <div v-if="selection.college_major_field_of_study || selection.collegeMajorFieldOfStudy" class="flex flex-col">
                        <span class="text-xs font-semibold text-gray-500 uppercase mb-1">College Major/Field of Study or Work</span>
                        <span class="text-sm text-gray-900">{{ selection.college_major_field_of_study || selection.collegeMajorFieldOfStudy }}</span>
                    </div>
                    <div v-if="selection.application_deadline || selection.applicationDeadline" class="flex flex-col">
                        <span class="text-xs font-semibold text-gray-500 uppercase mb-1">Application Deadline</span>
                        <span class="text-sm text-gray-900">{{ formatDate(selection.application_deadline || selection.applicationDeadline) || 'N/A' }}</span>
                    </div>
                    <div v-if="selection.application_requirements || selection.applicationRequirements" class="flex flex-col md:col-span-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase mb-1">Application Requirements</span>
                        <span class="text-sm text-gray-900 whitespace-pre-wrap">{{ selection.application_requirements || selection.applicationRequirements }}</span>
                    </div>
                    <div v-if="selection.preparing_by_description || selection.preparingByDescription" class="flex flex-col md:col-span-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase mb-1">I am preparing by</span>
                        <span class="text-sm text-gray-900 whitespace-pre-wrap">{{ selection.preparing_by_description || selection.preparingByDescription }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center mx-4">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Postsecondary Selections</p>
            <p class="text-xs text-gray-500 mt-1">Postsecondary Selections information is not available at this time.</p>
        </div>
    </div>

    <!-- Volunteer/Work Experience -->
    <div class="col-span-1 bg-white rounded-lg border-r py-5 text-center mt-2">
        <div class="flex items-end justify-between mb-4 px-4">
            <p class="text-sm text-left font-semibold">Volunteer/Work Experience</p>
            <button 
                v-if="!isParent"
                class="bg-blue-500 px-2 py-1.5 text-white border border-blue-600 rounded-lg shadow-lg hover:bg-blue-600"
                @click="openVolunteerModal()"
                title="Add Volunteer/Work Experience">
                <i class="fa-solid fa-plus text-white"></i>
            </button>
        </div>
        <hr class="mx-4 mb-4">
        <div v-if="volunteerExperiences && volunteerExperiences.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 px-4">
            <div v-for="exp in volunteerExperiences" :key="exp.uuid" 
                class="w-full text-left px-6 py-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow bg-gray-50">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="font-semibold text-base text-gray-900 flex-1">
                        {{ exp.activity || 'N/A' }}
                    </h4>
                    <div v-if="!isParent" class="flex gap-2 ml-4">
                        <button 
                            @click="openVolunteerModal(exp)" 
                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors"
                            title="Edit">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button 
                            @click="deleteVolunteerExperience(exp)" 
                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors"
                            title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Role:</span>
                        <span class="text-sm text-gray-900">{{ exp.role || 'N/A' }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Grade Level:</span>
                        <span class="text-sm text-gray-900">{{ exp.gradeLevel?.name || 'N/A' }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Dates:</span>
                        <span class="text-sm text-gray-900">{{ exp.dates || 'N/A' }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Total Hours:</span>
                        <span class="text-sm text-gray-900">{{ exp.totalHours || 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Volunteer/Work Experience</p>
            <p class="text-xs text-gray-500 mt-1">Volunteer/Work Experience information is not available at this time.</p>
        </div>
    </div>

    <!-- Extracurricular Activities -->
    <div class="col-span-1 bg-white rounded-lg border-r py-5 text-center mt-2">
        <div class="flex items-end justify-between mb-4 px-4">
            <p class="text-sm text-left font-semibold">Extracurricular Activities (Leadership/Clubs/Sports)</p>
            <button 
                v-if="!isParent"
                class="bg-blue-500 px-2 py-1.5 text-white border border-blue-600 rounded-lg shadow-lg hover:bg-blue-600"
                @click="openExtracurricularModal()"
                title="Add Extracurricular Activity">
                <i class="fa-solid fa-plus text-white"></i>
            </button>
        </div>
        <hr class="mx-4 mb-4">
        <div v-if="extracurricularActivities && extracurricularActivities.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 px-4">
            <div v-for="activity in extracurricularActivities" :key="activity.uuid" 
                class="w-full text-left px-6 py-4 border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow bg-gray-50">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="font-semibold text-base text-gray-900 flex-1">
                        {{ activity.activity || 'N/A' }}
                    </h4>
                    <div v-if="!isParent" class="flex gap-2 ml-4">
                        <button 
                            @click="openExtracurricularModal(activity)" 
                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded transition-colors"
                            title="Edit">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button 
                            @click="deleteExtracurricularActivity(activity)" 
                            class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition-colors"
                            title="Delete">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Role:</span>
                        <span class="text-sm text-gray-900">{{ activity.role || 'N/A' }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Grade Level:</span>
                        <span class="text-sm text-gray-900">{{ activity.gradeLevel?.name || 'N/A' }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Dates:</span>
                        <span class="text-sm text-gray-900">{{ activity.dates || 'N/A' }}</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Total Hours:</span>
                        <span class="text-sm text-gray-900">{{ activity.totalHours || 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No Extracurricular Activities</p>
            <p class="text-xs text-gray-500 mt-1">Extracurricular Activities information is not available at this time.</p>
        </div>
    </div>

    <!-- Career Planning Checklist Kanban -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 mt-2">
        <p class="text-sm text-left mx-4 font-semibold mb-4">College & Career Prep Checklist</p>
        <hr class="mx-4 mb-4">
        <div v-if="checklistLoading" class="p-4 text-center">
            <i class="fa-solid fa-spinner fa-spin text-blue-500 text-2xl"></i>
            <p class="text-sm text-gray-600 mt-2">Loading checklists...</p>
        </div>
        <div v-else-if="sortedGradeChecklistBoards.length > 0" class="px-4">
            <KanBanView>
                <div 
                    v-for="(board, boardIndex) in sortedGradeChecklistBoards" 
                    :key="board.gradeLevel.uuid"
                    class="flex h-[500px] w-80 flex-shrink-0 flex-col rounded-lg overflow-hidden relative"
                    :class="getBoardGradientClasses(boardIndex)">
                    <div class="text-white shadow-md px-4 py-3" :class="getBoardHeaderGradientClasses(boardIndex)">
                        <h3 class="font-semibold text-[17px] text-white">
                            {{ board.gradeLevel.name || `Grade ${board.gradeLevel.code}` }}
                        </h3>
                    </div>
                    <div class="scroller-thin-y scroller-hidden min-h-0 flex-1 p-4" :class="getBoardContentGradientClasses(boardIndex)">
                        <div v-if="board.checklists.length === 0" class="mt-2 rounded-md border-2 border-dashed border-gray-400 p-2 text-sm text-gray-400 dark:border-gray-500 dark:text-gray-500">
                            No checklists available
                        </div>
                        <div v-else class="mt-2 space-y-3">
                            <div 
                                v-for="checklist in board.checklists" 
                                :key="checklist.uuid"
                                class="rounded-md border-2 bg-white p-3 shadow-sm dark:bg-dark-body dark:text-gray-400"
                                :class="{
                                    'border-gray-400': true,
                                }">
                                <div class="space-y-2">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">
                                        {{ checklist.title }}
                                    </p>
                                    <div v-if="checklist.activities && checklist.activities.length > 0" class="space-y-2">
                                        <div 
                                            v-for="(activity, index) in checklist.activities" 
                                            :key="activity.uuid || index"
                                            class="flex items-start space-x-2 text-xs">
                                            <input 
                                                type="checkbox" 
                                                :checked="activity.completed || false"
                                                @change="handleActivityChange(checklist.uuid, activity.uuid, $event.target.checked)"
                                                class="mt-0.5 h-4 w-4 text-blue-600 rounded border-gray-300 cursor-pointer" />
                                            <span :class="{ 'line-through text-gray-500': activity.completed }" class="text-gray-700 dark:text-gray-400">
                                                {{ activity.title || activity.name || activity }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </KanBanView>
        </div>
        <div v-else class="mt-6 p-4 bg-gray-50 border border-gray-200 rounded-lg text-center mx-4">
            <i class="fa-solid fa-info-circle text-blue-500 text-2xl mb-2"></i>
            <p class="text-sm text-gray-600 font-medium">No College & Career Prep Checklist</p>
            <p class="text-xs text-gray-500 mt-1">College & Career Prep Checklist information is not available at this time.</p>
        </div>
    </div>

    <!-- Postsecondary Selection Modal -->
    <BaseModal :visibility="showPostsecondaryModal" @close="closePostsecondaryModal" size="lg">
        <template #title>
            {{ editingPostsecondary ? 'Edit' : 'Add' }} Postsecondary Selection
        </template>
        <div class="space-y-4">
            <BaseInput 
                v-model="postsecondaryForm.college_name_workplace_military_branch" 
                label="College Name/Workplace/Military Branch" 
                required />
            <BaseInput 
                v-model="postsecondaryForm.required_gpa" 
                type="number" 
                step="0.01" 
                min="0" 
                max="5"
                label="Required GPA for Admission" />
            <BaseInput 
                v-model="postsecondaryForm.required_test_score" 
                label="Required Test Score for Admission" />
            <BaseInput 
                v-model="postsecondaryForm.college_major_field_of_study" 
                label="College Major/Field of Study or Work" />
            <DatePicker 
                v-model="postsecondaryForm.application_deadline" 
                label="Application Deadline" />
            <BaseTextarea 
                v-model="postsecondaryForm.application_requirements" 
                label="Application Requirements" 
                :rows="3" />
            <BaseTextarea 
                v-model="postsecondaryForm.preparing_by_description" 
                label="I am preparing by" 
                :rows="3" />
        </div>
        <template #footer>
            <div class="flex justify-end gap-2">
                <BaseButton design="secondary" @click="closePostsecondaryModal">Cancel</BaseButton>
                <BaseButton design="primary" @click="savePostsecondarySelection">Save</BaseButton>
            </div>
        </template>
    </BaseModal>

    <!-- Volunteer/Work Experience Modal -->
    <BaseModal :visibility="showVolunteerModal" @close="closeVolunteerModal">
        <template #title>
            {{ editingVolunteer ? 'Edit' : 'Add' }} Volunteer/Work Experience
        </template>
        <div class="space-y-4">
            <BaseInput 
                v-model="volunteerForm.activity" 
                label="Activity" 
                required />
            <BaseInput 
                v-model="volunteerForm.role" 
                label="Role" />
            <BaseSelect 
                v-model="volunteerForm.grade_level" 
                label="Grade Level" 
                :options="gradeLevels" 
                value-prop="uuid" 
                label-prop="name" />
            <BaseInput 
                v-model="volunteerForm.dates" 
                label="Dates" />
            <BaseInput 
                v-model="volunteerForm.total_hours" 
                label="Total Hours" />
        </div>
        <template #footer>
            <div class="flex justify-end gap-2">
                <BaseButton design="secondary" @click="closeVolunteerModal">Cancel</BaseButton>
                <BaseButton design="primary" @click="saveVolunteerExperience">Save</BaseButton>
            </div>
        </template>
    </BaseModal>

    <!-- Extracurricular Activity Modal -->
    <BaseModal :visibility="showExtracurricularModal" @close="closeExtracurricularModal">
        <template #title>
            {{ editingExtracurricular ? 'Edit' : 'Add' }} Extracurricular Activity
        </template>
        <div class="space-y-4">
            <BaseInput 
                v-model="extracurricularForm.activity" 
                label="Activity" 
                required />
            <BaseInput 
                v-model="extracurricularForm.role" 
                label="Role" />
            <BaseSelect 
                v-model="extracurricularForm.grade_level" 
                label="Grade Level" 
                :options="gradeLevels" 
                value-prop="uuid" 
                label-prop="name" />
            <BaseInput 
                v-model="extracurricularForm.dates" 
                label="Dates" />
            <BaseInput 
                v-model="extracurricularForm.total_hours" 
                label="Total Hours" />
        </div>
        <template #footer>
            <div class="flex justify-end gap-2">
                <BaseButton design="secondary" @click="closeExtracurricularModal">Cancel</BaseButton>
                <BaseButton design="primary" @click="saveExtracurricularActivity">Save</BaseButton>
            </div>
        </template>
    </BaseModal>
</template>

<script setup>
import { reactive, ref, inject, computed, watch, onMounted } from "vue"
import { useStore } from "vuex"
import BaseModal from "@core/components/Ui/BaseModal.vue"
import BaseSelect from "@core/components/Forms/BaseSelect.vue"
import BaseButton from "@core/components/Forms/BaseButton.vue"
import BaseInput from "@core/components/Forms/BaseInput.vue"
import BaseTextarea from "@core/components/Forms/BaseTextarea.vue"
import DatePicker from "@core/components/Forms/DatePicker.vue"
import KanBanView from "@core/components/Ui/KanBanView.vue"
import { confirmDelete } from "@core/helpers/alert"

const store = useStore()
const toast = useToast()
const emitter = inject("emitter")

const props = defineProps({
    student: Object,
    postsecondaryChoices: Array,
    careerPathways: Array,
    gradeLevels: {
        type: Array,
        default: () => []
    },
    schoolYears: {
        type: Array,
        default: () => []
    }
})

// Check if user is parent
const isParent = computed(() => store.getters["auth/user/hasAnyRole"](['parent-profile', 'guardian']))

// Computed properties for data
const postsecondarySelections = computed(() => {
    const data = props.student?.postsecondarySelections || props.student?.postsecondary_selections || []
    return Array.isArray(data) ? data : []
})
const volunteerExperiences = computed(() => {
    const data = props.student?.volunteerWorkExperiences || props.student?.volunteer_work_experiences || []
    return Array.isArray(data) ? data : []
})
const extracurricularActivities = computed(() => {
    const data = props.student?.extracurricularActivities || props.student?.extracurricular_activities || []
    return Array.isArray(data) ? data : []
})

// Academic Career Planning
const showAcademicCareerPlanningModal = ref(false)

const academicCareerPlanningForm = reactive({
    postsecondaryChoice: [],
    careerPathway: [],
    student: props.student?.uuid || '',
})

// Open modal and preload from current student
watch(
    () => showAcademicCareerPlanningModal.value,
    (open) => {
        if (open) {
            academicCareerPlanningForm.postsecondaryChoice = (props.student?.postsecondaryChoices || []).map(x => x.uuid)
            academicCareerPlanningForm.careerPathway = (props.student?.careerPathways || []).map(x => x.uuid)
        }
    }
)

// Maps for quick lookup
const psMap = computed(() =>
    Object.fromEntries((props.postsecondaryChoices || []).map(o => [o.uuid, o.titles ?? o.name ?? '—']))
)
const cpMap = computed(() =>
    Object.fromEntries((props.careerPathways || []).map(o => [o.uuid, o.titles ?? o.name ?? '—']))
)

const psTitle = (uuid) => psMap.value[uuid] || '—'
const cpTitle = (uuid) => cpMap.value[uuid] || '—'

function removePs(uuid) {
    academicCareerPlanningForm.postsecondaryChoice = academicCareerPlanningForm.postsecondaryChoice.filter(u => u !== uuid)
}
function removeCp(uuid) {
    academicCareerPlanningForm.careerPathway = academicCareerPlanningForm.careerPathway.filter(u => u !== uuid)
}

const submitAcademicCareerPlanning = async () => {
    try {
        const payload = {
            student: props.student?.uuid,
            postsecondary_choice: academicCareerPlanningForm.postsecondaryChoice,
            career_pathway: academicCareerPlanningForm.careerPathway,
        }

        await store.dispatch('student/studentProfile/addAcademicCareerPlanning', {
            form: payload,
        })

        showAcademicCareerPlanningModal.value = false
        emitter.emit("studentUpdate")

    } catch (error) {
        console.error(error)
    }
}

// Postsecondary Selections
const showPostsecondaryModal = ref(false)
const editingPostsecondary = ref(null)
const postsecondaryForm = reactive({
    college_name_workplace_military_branch: '',
    required_gpa: null,
    required_test_score: '',
    college_major_field_of_study: '',
    application_deadline: null,
    application_requirements: '',
    preparing_by_description: '',
})

function openPostsecondaryModal(selection = null) {
    editingPostsecondary.value = selection
    if (selection) {
        postsecondaryForm.college_name_workplace_military_branch = selection.college_name_workplace_military_branch || selection.collegeNameWorkplaceMilitaryBranch || ''
        postsecondaryForm.required_gpa = selection.required_gpa || selection.requiredGpa || null
        postsecondaryForm.required_test_score = selection.required_test_score || selection.requiredTestScore || ''
        postsecondaryForm.college_major_field_of_study = selection.college_major_field_of_study || selection.collegeMajorFieldOfStudy || ''
        postsecondaryForm.application_deadline = selection.application_deadline || selection.applicationDeadline || null
        postsecondaryForm.application_requirements = selection.application_requirements || selection.applicationRequirements || ''
        postsecondaryForm.preparing_by_description = selection.preparing_by_description || selection.preparingByDescription || ''
    } else {
        Object.assign(postsecondaryForm, {
            college_name_workplace_military_branch: '',
            required_gpa: null,
            required_test_score: '',
            college_major_field_of_study: '',
            application_deadline: null,
            application_requirements: '',
            preparing_by_description: '',
        })
    }
    showPostsecondaryModal.value = true
}

function closePostsecondaryModal() {
    showPostsecondaryModal.value = false
    editingPostsecondary.value = null
}

async function savePostsecondarySelection() {
    try {
        const payload = {
            student: props.student?.uuid,
            ...postsecondaryForm
        }

        if (editingPostsecondary.value) {
            await store.dispatch('student/studentProfile/updatePostsecondarySelection', {
                uuid: editingPostsecondary.value.uuid,
                form: payload,
            })
        } else {
            await store.dispatch('student/studentProfile/addPostsecondarySelection', {
                form: payload,
            })
        }

        closePostsecondaryModal()
        emitter.emit("studentUpdate")
    } catch (error) {
        console.error(error)
    }
}

async function deletePostsecondarySelection(selection) {
    if (!(await confirmDelete('Are you sure you want to delete this postsecondary selection?'))) return
    
    try {
        await store.dispatch('student/studentProfile/deletePostsecondarySelection', {
            uuid: selection.uuid,
            student: props.student?.uuid,
        })
        emitter.emit("studentUpdate")
    } catch (error) {
        console.error(error)
    }
}

// Volunteer/Work Experience
const showVolunteerModal = ref(false)
const editingVolunteer = ref(null)
const volunteerForm = reactive({
    activity: '',
    role: '',
    grade_level: null,
    dates: '',
    total_hours: '',
})

function openVolunteerModal(experience = null) {
    editingVolunteer.value = experience
    if (experience) {
        volunteerForm.activity = experience.activity || ''
        volunteerForm.role = experience.role || ''
        const gradeLevel = experience.grade_level || experience.gradeLevel
        volunteerForm.grade_level = gradeLevel?.uuid || null
        volunteerForm.dates = experience.dates || ''
        volunteerForm.total_hours = experience.total_hours || experience.totalHours || ''
    } else {
        Object.assign(volunteerForm, {
            activity: '',
            role: '',
            grade_level: null,
            dates: '',
            total_hours: '',
        })
    }
    showVolunteerModal.value = true
}

function closeVolunteerModal() {
    showVolunteerModal.value = false
    editingVolunteer.value = null
}

async function saveVolunteerExperience() {
    try {
        const payload = {
            student: props.student?.uuid,
            ...volunteerForm
        }

        if (editingVolunteer.value) {
            await store.dispatch('student/studentProfile/updateVolunteerWorkExperience', {
                uuid: editingVolunteer.value.uuid,
                form: payload,
            })
        } else {
            await store.dispatch('student/studentProfile/addVolunteerWorkExperience', {
                form: payload,
            })
        }

        closeVolunteerModal()
        emitter.emit("studentUpdate")
    } catch (error) {
        console.error(error)
    }
}

async function deleteVolunteerExperience(experience) {
    if (!(await confirmDelete('Are you sure you want to delete this volunteer/work experience?'))) return
    
    try {
        await store.dispatch('student/studentProfile/deleteVolunteerWorkExperience', {
            uuid: experience.uuid,
            student: props.student?.uuid,
        })
        emitter.emit("studentUpdate")
    } catch (error) {
        console.error(error)
    }
}

// Extracurricular Activities
const showExtracurricularModal = ref(false)
const editingExtracurricular = ref(null)
const extracurricularForm = reactive({
    activity: '',
    role: '',
    grade_level: null,
    dates: '',
    total_hours: '',
})

function openExtracurricularModal(activity = null) {
    editingExtracurricular.value = activity
    if (activity) {
        extracurricularForm.activity = activity.activity || ''
        extracurricularForm.role = activity.role || ''
        const gradeLevel = activity.grade_level || activity.gradeLevel
        extracurricularForm.grade_level = gradeLevel?.uuid || null
        extracurricularForm.dates = activity.dates || ''
        extracurricularForm.total_hours = activity.total_hours || activity.totalHours || ''
    } else {
        Object.assign(extracurricularForm, {
            activity: '',
            role: '',
            grade_level: null,
            dates: '',
            total_hours: '',
        })
    }
    showExtracurricularModal.value = true
}

function closeExtracurricularModal() {
    showExtracurricularModal.value = false
    editingExtracurricular.value = null
}

async function saveExtracurricularActivity() {
    try {
        const payload = {
            student: props.student?.uuid,
            ...extracurricularForm
        }

        if (editingExtracurricular.value) {
            await store.dispatch('student/studentProfile/updateExtracurricularActivity', {
                uuid: editingExtracurricular.value.uuid,
                form: payload,
            })
        } else {
            await store.dispatch('student/studentProfile/addExtracurricularActivity', {
                form: payload,
            })
        }

        closeExtracurricularModal()
        emitter.emit("studentUpdate")
    } catch (error) {
        console.error(error)
    }
}

async function deleteExtracurricularActivity(activity) {
    if (!(await confirmDelete('Are you sure you want to delete this extracurricular activity?'))) return
    
    try {
        await store.dispatch('student/studentProfile/deleteExtracurricularActivity', {
            uuid: activity.uuid,
            student: props.student?.uuid,
        })
        emitter.emit("studentUpdate")
    } catch (error) {
        console.error(error)
    }
}

// Helper functions
function formatDate(date) {
    if (!date) return ''
    try {
        // If it's already a formatted date string (contains month name), return as is
        if (typeof date === 'string' && /[A-Za-z]{3}/.test(date)) {
            return date
        }
        
        // Handle both string and date object
        let d
        if (date instanceof Date) {
            d = date
        } else if (typeof date === 'string') {
            // Try parsing as Y-m-d format first (most common from backend)
            if (date.match(/^\d{4}-\d{2}-\d{2}/)) {
                const parts = date.split('-')
                d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]))
            } else {
                d = new Date(date)
            }
        } else {
            return ''
        }
        
        // Check if date is valid
        if (isNaN(d.getTime())) {
            return ''
        }
        
        return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
    } catch (error) {
        console.error('Error formatting date:', error, date)
        return ''
    }
}

// Grade Checklist Kanban
const checklistLoading = ref(false)
const gradeChecklistBoards = ref([])

// Sort grade checklist boards by grade level (lower to higher - ascending)
const sortedGradeChecklistBoards = computed(() => {
    return [...gradeChecklistBoards.value].sort((a, b) => {
        const codeA = a.gradeLevel?.code || "";
        const codeB = b.gradeLevel?.code || "";
        // Extract numeric part for comparison
        const numA = parseInt(codeA.match(/\d+/)?.[0] || "0");
        const numB = parseInt(codeB.match(/\d+/)?.[0] || "0");
        return numA - numB; // Ascending order (lower to higher)
    });
});

// Fetch grade checklists for student (all logic handled in backend)
const fetchGradeChecklists = async () => {
    const studentUuid = props.student?.uuid
    if (!studentUuid) {
        gradeChecklistBoards.value = []
        return
    }
    
    checklistLoading.value = true
    try {
        const response = await store.dispatch('careerPlanning/gradeChecklist/getForStudent', {
            student_uuid: studentUuid
        })
        gradeChecklistBoards.value = response?.boards || []
    } catch (error) {
        console.error('Error fetching grade checklists:', error)
        gradeChecklistBoards.value = []
    } finally {
        checklistLoading.value = false
    }
}

// Gradient color functions for kanban boards
const getBoardGradientClasses = (index) => {
    const gradients = [
        'bg-gradient-to-br from-blue-100 to-white border border-blue-200',
        'bg-gradient-to-b from-orange-50 to-transparent border-2 border-orange-400',
        'bg-gradient-to-b from-green-100 to-transparent border-2 border-green-500',
        'bg-gradient-to-b from-pink-100 to-transparent border-2 border-pink-400',
        'bg-gradient-to-b from-indigo-100 to-transparent border-2 border-indigo-400',
        'bg-gradient-to-b from-teal-100 to-transparent border-2 border-teal-400',
        'bg-gradient-to-b from-cyan-100 to-transparent border-2 border-cyan-400',
        'bg-gradient-to-b from-amber-100 to-transparent border-2 border-amber-400',
    ]
    return gradients[index % gradients.length] + ' dark:bg-neutral-800'
}

const getBoardHeaderGradientClasses = (index) => {
    const gradients = [
        'bg-gradient-to-br from-blue-600 via-blue-500 to-blue-400 border-b border-blue-700 dark:border-blue-500',
        'bg-gradient-to-r from-orange-400 to-orange-600 border-b border-orange-500',
        'bg-gradient-to-r from-green-600 to-green-800 border-b border-green-700',
        'bg-gradient-to-r from-pink-500 to-pink-700 border-b border-pink-600',
        'bg-gradient-to-r from-indigo-500 to-indigo-700 border-b border-indigo-600',
        'bg-gradient-to-r from-teal-500 to-teal-700 border-b border-teal-600',
        'bg-gradient-to-r from-cyan-500 to-cyan-700 border-b border-cyan-600',
        'bg-gradient-to-r from-amber-500 to-amber-700 border-b border-amber-600',
    ]
    return gradients[index % gradients.length]
}

const getBoardContentGradientClasses = (index) => {
    const gradients = [
        'bg-gradient-to-br from-blue-100/50 to-white',
        'bg-gradient-to-br from-orange-50/50 to-transparent',
        'bg-gradient-to-br from-green-100/50 to-transparent',
        'bg-gradient-to-br from-pink-100/50 to-transparent',
        'bg-gradient-to-br from-indigo-100/50 to-transparent',
        'bg-gradient-to-br from-teal-100/50 to-transparent',
        'bg-gradient-to-br from-cyan-100/50 to-transparent',
        'bg-gradient-to-br from-amber-100/50 to-transparent',
    ]
    return gradients[index % gradients.length]
}

// Handle activity checkbox change
const handleActivityChange = async (checklistUuid, activityUuid, completed) => {
    const studentUuid = props.student?.uuid
    if (!studentUuid) {
        toast.error('Student information is required')
        return
    }

    if (!activityUuid) {
        toast.error('Activity UUID is required')
        return
    }

    try {
        // Optimistically update the UI
        const board = gradeChecklistBoards.value.find(b => 
            b.checklists.some(c => c.uuid === checklistUuid)
        )
        if (board) {
            const checklist = board.checklists.find(c => c.uuid === checklistUuid)
            if (checklist && checklist.activities) {
                const activity = checklist.activities.find(a => a.uuid === activityUuid)
                if (activity) {
                    activity.completed = completed
                }
            }
        }

        // Save to backend
        await store.dispatch('careerPlanning/gradeChecklist/updateActivityCompletion', {
            student_uuid: studentUuid,
            checklist_uuid: checklistUuid,
            activity_uuid: activityUuid,
            completed: completed,
        })

        toast.success(completed ? 'Activity marked as completed' : 'Activity marked as incomplete')
    } catch (error) {
        console.error('Error updating activity completion:', error)
        toast.error('Failed to update activity completion')
        
        // Revert optimistic update
        const board = gradeChecklistBoards.value.find(b => 
            b.checklists.some(c => c.uuid === checklistUuid)
        )
        if (board) {
            const checklist = board.checklists.find(c => c.uuid === checklistUuid)
            if (checklist && checklist.activities) {
                const activity = checklist.activities.find(a => a.uuid === activityUuid)
                if (activity) {
                    activity.completed = !completed
                }
            }
        }
        
        // Refetch to ensure consistency
        fetchGradeChecklists()
    }
}

// Watch for student updates
watch(() => props.student?.uuid, () => {
    fetchGradeChecklists()
}, { immediate: true })

onMounted(() => {
    fetchGradeChecklists()
})
</script>
