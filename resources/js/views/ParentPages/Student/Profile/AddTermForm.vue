<template>

  <div class="mb-4">

    <BaseSelect v-model="form.activity" name="activity" :label="`Select Sport & Activity`"
      :options="props.sportAndActivities" label-prop="title" value-prop="uuid" />
  </div>

  <div class="mt-6 text-right">
    <BaseButton design="primary" @click="submit" :loading="isSubmitting" class="mt-4">
      Submit Activity
    </BaseButton>
  </div>

</template>

<script setup>
import { reactive, ref } from 'vue'
import { useStore } from 'vuex'

const props = defineProps({
  studentUUID: {
    type: String,
    required: true
  },
  sportAndActivities: {
    type: Array,
    required: true
  }
})


const emit = defineEmits(['submitted'])

const store = useStore()
const isSubmitting = ref(false)

// Local reactive form state
const form = reactive({
  activity: '',
  student: props.studentUUID,
  // Add more fields as needed
})


const submit = async () => {
  isSubmitting.value = true
  try {
    await store.dispatch('student/studentProfile/addActivity', {
      form: form
    })
    emit('submitted') // Tell parent to refresh list / close modal
  } catch (error) {
    console.error(error)
  } finally {
    isSubmitting.value = false
  }
}
</script>
