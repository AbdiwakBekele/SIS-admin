<template>
    <PageHeader
        :title="
            $trans(route.meta.trans, {
                attribute: $trans(route.meta.label),
            })
        "
        :navs="[
            {
                label: $trans('admission_inquiry.my_profile'),
                path: 'MyParentProfile',
            },
        ]"
    >
        <PageHeaderAction
            name="MyParentProfile"
            :title="$trans('admission_inquiry.my_profile')"
            :actions="[]"
        >
            <BaseButton design="white" @click="router.push({ name: 'MyParentProfile' })">
                Go back to my profile
            </BaseButton>
        </PageHeaderAction>
    </PageHeader>

    <ParentTransition appear :visibility="true">
        <FormAction
            :pre-requisites="true"
            @setPreRequisites="setPreRequisites"
            :init-url="initUrl"
            :pre-requisite-url="preRequisiteUrl"
            :init-form="initForm"
            :form="form"
            :set-form="setForm"
            action="updateMyProfile"
            no-data-fetch
            stay-on
            :redirect="{ name: 'MyParentProfile' }"
            :after-submit="afterSubmit"
        >
            <!-- Basic Information -->
            <BaseFieldset class="mt-4">
                <template #legend>{{ $trans('contact.basic_information') }}</template>
                <div class="grid grid-cols-3 gap-6">
                    <!-- First Name -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.firstName"
                            name="firstName"
                            :label="$trans('contact.props.first_name')"
                        />
                    </div>

                    <!-- Middle Name -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.middleName"
                            name="middleName"
                            :label="$trans('contact.props.middle_name')"
                        />
                    </div>

                    <!-- Last Name -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.lastName"
                            name="lastName"
                            :label="$trans('contact.props.last_name')"
                        />
                    </div>

                    <!-- Email -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="email"
                            v-model="form.email"
                            name="email"
                            :label="$trans('contact.props.email')"
                            disabled
                        />
                    </div>

                    <!-- Phone Number -->
                    <div class="col-span-3 sm:col-span-1">
                        <PhoneInput
                            v-model="form.phoneNumber"
                            name="phoneNumber"
                            :label="`Phone Number`"
                        />
                    </div>

                    <!-- Nationality -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.nationality"
                            name="nationality"
                            :label="$trans('contact.props.nationality')"
                        />
                    </div>

                    <!-- Gender -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseLabel>{{ $trans("contact.props.gender") }}</BaseLabel>
                        <BaseRadioGroup
                            top-margin
                            :options="preRequisites.genders || []"
                            name="gender"
                            v-model="form.gender"
                            horizontal
                        />
                    </div>

                    <!-- Date of Birth -->
                    <div class="col-span-3 sm:col-span-1">
                        <DatePicker
                            v-model="form.birthDate"
                            name="birthDate"
                            :label="$trans('contact.props.birth_date')"
                            no-clear
                        />
                    </div>

                    <!-- Native Language -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSelect
                            v-model="form.nativeLanguage"
                            name="nativeLanguage"
                            :label="$trans('contact.props.native_language')"
                            :options="preRequisites.languages || []"
                            label-prop="name"
                            value-prop="name"
                        />
                    </div>

                    <!-- Race -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseSelect
                            v-model="form.race"
                            name="race"
                            :label="$trans('contact.props.race')"
                            :options="preRequisites.races || []"
                            label-prop="name"
                            value-prop="name"
                        />
                    </div>

                    <!-- Religious Affiliation -->
                    <!-- <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.religiousAffiliation"
                            name="religiousAffiliation"
                            :label="$trans('contact.props.religious_affiliation')"
                        />
                    </div> -->

                    <!-- Family Status -->
                    <!-- <div class="col-span-3 sm:col-span-1">
                        <BaseSelect
                            v-model="form.familyStatus"
                            name="familyStatus"
                            :label="$trans('contact.props.family_status')"
                            :options="preRequisites.maritalStatuses || []"
                            label-prop="label"
                            value-prop="value"
                        />
                    </div> -->

                    <!-- Time Zone -->
                    <!-- <div class="col-span-3 sm:col-span-1">
                        <BaseSelect
                            v-model="form.timeZone"
                            name="timeZone"
                            :label="$trans('contact.props.time_zone')"
                            :options="preRequisites.timezones || []"
                            label-prop="label"
                            value-prop="value"
                        />
                    </div> -->
                </div>
            </BaseFieldset>

            <!-- Current Address -->
            <BaseFieldset class="mt-4">
                <template #legend>{{ $trans('contact.props.current_address') || 'Current Address' }}</template>
                <div class="grid grid-cols-3 gap-6">
                    <!-- Address Line 1 -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.line1"
                            name="line1"
                            :label="$trans('contact.props.address.address_line1') || 'Address Line 1'"
                        />
                    </div>

                    <!-- Address Line 2 -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.line2"
                            name="line2"
                            :label="$trans('contact.props.address.address_line2') || 'Address Line 2'"
                        />
                    </div>

                    <!-- City -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.city"
                            name="city"
                            :label="$trans('contact.props.address.city') || 'City'"
                        />
                    </div>

                    <!-- State -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.state"
                            name="state"
                            :label="$trans('contact.props.address.state') || 'State'"
                        />
                    </div>

                    <!-- Postal Code -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.postalCode"
                            name="postalCode"
                            :label="$trans('contact.props.address.zipcode') || 'Postal Code'"
                        />
                    </div>

                    <!-- Country -->
                    <div class="col-span-3 sm:col-span-1">
                        <BaseInput
                            type="text"
                            v-model="form.country"
                            name="country"
                            :label="$trans('contact.props.address.country') || 'Country'"
                        />
                    </div>
                </div>
            </BaseFieldset>
        </FormAction>
    </ParentTransition>
</template>

<script>
export default {
    name: "MyParentProfileEdit",
}
</script>

<script setup>
import { reactive, inject, onMounted } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useStore } from "vuex"
import { getFormErrors } from "@core/helpers/action"

const route = useRoute()
const router = useRouter()
const store = useStore()
const emitter = inject("emitter")
const $trans = inject("$trans")

const initForm = {
    firstName: "",
    middleName: "",
    lastName: "",
    email: "",
    phoneNumber: "",
    nationality: "",
    gender: "",
    birthDate: "",
    nativeLanguage: "",
    race: "",
    religiousAffiliation: "",
    familyStatus: "",
    timeZone: "",
    line1: "",
    line2: "",
    city: "",
    state: "",
    postalCode: "",
    country: "",
}

const initUrl = "guardian/"
const preRequisiteUrl = "guardian/"
const formErrors = getFormErrors("guardian/my-profile/")

const preRequisites = reactive({
    genders: [],
    languages: [],
    maritalStatuses: [],
    timezones: [],
    races: [],
})

const form = reactive({ ...initForm })

const fetchData = reactive({
    isLoaded: false,
})

const setPreRequisites = (data) => {
    if (data) {
        Object.assign(preRequisites, {
            genders: data.genders || [],
            languages: data.languages || [],
            maritalStatuses: data.maritalStatuses || [],
            timezones: data.timezones || [],
            races: data.races || [],
        })
        
        // Re-match form values after prerequisites are loaded
        if (fetchData.isLoaded) {
            const currentFormData = { ...form }
            setForm(currentFormData)
        }
    }
}

const setForm = function setForm(data) {
    const address = data.address || data.currentAddress || {}
    const presentAddress = Array.isArray(address) && address[0]?.present ? address[0].present : (address.present || {})

    // Convert gender from capitalized to lowercase for dropdown matching
    let genderValue = data.gender || ""
    if (genderValue && typeof genderValue === 'string') {
        genderValue = genderValue.toLowerCase()
    }

    // Match native language by name if it's a string
    let nativeLanguageValue = data.nativeLanguage || data.native_language || ""
    if (nativeLanguageValue && preRequisites.languages?.length > 0) {
        const matchedLang = preRequisites.languages.find(lang => 
            lang.name === nativeLanguageValue || lang.code === nativeLanguageValue
        )
        if (matchedLang) {
            nativeLanguageValue = matchedLang.name
        }
    }

    // Match family status value
    let familyStatusValue = data.familyStatus || data.maritalStatus?.value || data.meta?.family_status || ""
    if (familyStatusValue && preRequisites.maritalStatuses?.length > 0) {
        const matchedStatus = preRequisites.maritalStatuses.find(status => 
            status.value === familyStatusValue || status.label === familyStatusValue
        )
        if (matchedStatus) {
            familyStatusValue = matchedStatus.value
        }
    }

    // Match time zone value
    let timeZoneValue = data.timeZone || data.meta?.time_zone || ""
    if (timeZoneValue && preRequisites.timezones?.length > 0) {
        const matchedTz = preRequisites.timezones.find(tz => 
            tz.value === timeZoneValue || tz.label === timeZoneValue
        )
        if (matchedTz) {
            timeZoneValue = matchedTz.value
        }
    }

    // Match race by name
    let raceValue = data.race || ""
    if (raceValue && preRequisites.races?.length > 0) {
        const matchedRace = preRequisites.races.find(race => 
            race.name === raceValue || race.code === raceValue
        )
        if (matchedRace) {
            raceValue = matchedRace.name
        }
    }

    const formData = {
        firstName: data.firstName || data.first_name || "",
        middleName: data.middleName || data.middle_name || "",
        lastName: data.lastName || data.last_name || "",
        email: data.email || "",
        phoneNumber: data.phoneNumber || data.phone_number || data.contact_number || "",
        nationality: data.nationality || "",
        gender: genderValue,
        birthDate: data.birthDate?.value || data.birthDate || data.birth_date?.value || data.birth_date || "",
        nativeLanguage: nativeLanguageValue,
        race: raceValue,
        religiousAffiliation: data.religiousAffiliation || data.meta?.religious_affiliation || "",
        familyStatus: familyStatusValue,
        timeZone: timeZoneValue,
        line1: presentAddress.address_line1 || presentAddress.line1 || data.line1 || "",
        line2: presentAddress.address_line2 || presentAddress.line2 || data.line2 || "",
        city: presentAddress.city || data.city || "",
        state: presentAddress.state || data.state || "",
        postalCode: presentAddress.zipcode || presentAddress.postal_code || data.postalCode || "",
        country: presentAddress.country || data.country || "",
    }

    Object.assign(form, formData)
    Object.assign(initForm, formData) // Update initForm so FormAction knows the initial state
    fetchData.isLoaded = true
}

const afterSubmit = () => {
    emitter.emit("guardianUpdated")
    router.push({ name: "MyParentProfile" })
}

onMounted(async () => {
    // Wait for prerequisites to be loaded (they're loaded by FormAction)
    await new Promise(resolve => {
        const checkPreRequisites = () => {
            if (preRequisites.genders?.length > 0 && preRequisites.languages?.length > 0) {
                resolve()
            } else {
                setTimeout(checkPreRequisites, 100)
            }
        }
        checkPreRequisites()
    })

    // Load current profile data
    try {
        const response = await store.dispatch("guardian/myProfile")
        if (response?.guardian) {
            const guardian = response.guardian
            setForm({
                firstName: guardian.firstName || guardian.first_name,
                middleName: guardian.middleName || guardian.middle_name,
                lastName: guardian.lastName || guardian.last_name,
                email: guardian.email,
                phoneNumber: guardian.phoneNumber || guardian.phone_number,
                nationality: guardian.nationality,
                gender: guardian.gender,
                birthDate: guardian.birthDate || guardian.birth_date,
                nativeLanguage: guardian.nativeLanguage || guardian.native_language,
                race: guardian.race,
                religiousAffiliation: guardian.religiousAffiliation || guardian.meta?.religious_affiliation,
                familyStatus: guardian.familyStatus || guardian.meta?.family_status,
                timeZone: guardian.timeZone || guardian.meta?.time_zone,
                address: guardian.address || guardian.currentAddress,
                currentAddress: guardian.currentAddress,
                line1: guardian.line1,
                line2: guardian.line2,
                city: guardian.city,
                state: guardian.state,
                postalCode: guardian.postalCode,
                country: guardian.country,
                meta: guardian.meta,
            })
        }
    } catch (error) {
        console.error("Failed to load profile:", error)
    }
})
</script>
