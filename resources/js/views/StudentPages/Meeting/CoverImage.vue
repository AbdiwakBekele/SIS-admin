<template>
    <div class="overflow-hidden bg-black">
        <div>
            <ImageUpload
                class="h-32 w-full lg:h-48"
                :disabled="disabled"
                :label="$trans('calendar.event.props.cover_image')"
                :src="event.coverImage"
                :upload-path="`calendar/events/${event.uuid}/assets/cover`"
                :remove-path="`calendar/events/${event.uuid}/assets/cover`"
                design="modern"
                :show-label="false"
                @uploaded="emit('refreshItem')"
                @removed="emit('refreshItem')"
            />
        </div>
    </div>
    <!-- <p
        class="mx-2 mt-4 text-sm text-gray-600 dark:text-gray-400"
        v-if="event.coverImage"
    >
        <i class="fas fa-circle-info"></i> {{ $trans("blog.assets.og_info") }}
    </p> -->
</template>

<script>
export default {
    name: "CalendarEventCoverImage",
}
</script>

<script setup>
const emit = defineEmits(["refreshItem"])

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
})
</script>
