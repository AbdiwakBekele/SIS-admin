<template>
    <div class="relative">
        <BaseButton design="white" class="shadow-sm" ref="buttonRef" @click="toggle">
            {{ label }}
            <ChevronDownIcon :class="['ml-1 h-5 w-5']" aria-hidden="true" />
        </BaseButton>
        
        <div v-show="isOpen" ref="popoverRef" class="bulk-action-popover" :style="{ ...popoverStyle, ...arrowStyle }">
            <div class="bulk-action-popover-arrow bulk-action-popover-arrow-right"></div>
            <div class="py-1">
                <a
                    v-for="bulkAction in bulkActions"
                    :key="bulkAction.name"
                    @click="handleAction(bulkAction.name)"
                    class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-gray-100 cursor-pointer"
                >
                    <i :class="[bulkAction.icon, 'mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 dark:text-gray-500']"></i>
                    {{ bulkAction.label }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BulkActionPopover',
}
</script>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick, watch } from 'vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

const emit = defineEmits(['action'])

const props = defineProps({
    bulkActions: {
        type: Array,
        required: true,
    },
    label: {
        type: String,
        default: 'Bulk Action',
    },
})

const isOpen = ref(false)
const buttonRef = ref(null)
const popoverRef = ref(null)
const popoverStyle = ref({})
const arrowOffset = ref('1.5rem')

const toggle = () => {
    isOpen.value = !isOpen.value
    if (isOpen.value) {
        nextTick(() => calculatePosition())
    }
}

const handleAction = (actionName) => {
    emit('action', actionName)
    close()
}

const close = () => {
    isOpen.value = false
}

const calculatePosition = () => {
    if (!buttonRef.value || !popoverRef.value) return
    
    const button = buttonRef.value.$el || buttonRef.value
    const buttonRect = button.getBoundingClientRect()
    const popoverWidth = 240 // w-60 = 15rem = 240px
    const spacing = 8 // Space between button and popover
    
    // Calculate popover position (right-aligned with button, below it)
    const right = window.innerWidth - buttonRect.right
    const top = buttonRect.bottom + spacing
    
    popoverStyle.value = {
        position: 'fixed',
        top: `${top}px`,
        right: `${right}px`,
        width: '15rem',
        zIndex: 50,
    }
    
    // Position arrow on the right side of the popover, aligned with button
    // Calculate arrow position to point at button center
    // Arrow is positioned from the right edge of the popover
    const buttonCenterX = buttonRect.left + buttonRect.width / 2
    const popoverRightEdge = window.innerWidth - right
    const popoverLeftEdge = popoverRightEdge - popoverWidth
    const distanceFromPopoverLeft = buttonCenterX - popoverLeftEdge
    const distanceFromPopoverRight = popoverWidth - distanceFromPopoverLeft
    
    // Arrow offset from right edge, clamped to stay within popover bounds
    // This positions the arrow on the right side, pointing to button center
    arrowOffset.value = `${Math.max(0.5, Math.min(distanceFromPopoverRight, popoverWidth - 0.5))}px`
}

const arrowStyle = computed(() => {
    return { '--arrow-offset': arrowOffset.value }
})

const onDocMouseDown = (e) => {
    if (!isOpen.value) return
    const el = popoverRef.value
    if (!el) return

    if (el === e.target || el.contains(e.target)) return
    if (buttonRef.value && (buttonRef.value.$el === e.target || buttonRef.value.$el?.contains(e.target))) return

    close()
}

const onDocKeyDown = (e) => {
    if (e.key === 'Escape' && isOpen.value) close()
}

onMounted(async () => {
    await nextTick()
    if (isOpen.value) {
        calculatePosition()
    }
    window.addEventListener('resize', calculatePosition)
    window.addEventListener('scroll', calculatePosition, true)
    document.addEventListener('mousedown', onDocMouseDown, true)
    document.addEventListener('keydown', onDocKeyDown)

    watch(isOpen, (newVal) => {
        if (newVal) {
            nextTick(() => calculatePosition())
        }
    })
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', calculatePosition)
    window.removeEventListener('scroll', calculatePosition, true)
    document.removeEventListener('mousedown', onDocMouseDown, true)
    document.removeEventListener('keydown', onDocKeyDown)
})
</script>

<style scoped>
.bulk-action-popover {
    background: white;
    border: 1px solid rgb(156 163 175);
    border-radius: 0.5rem;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    position: relative;
}

.dark .bulk-action-popover {
    background: rgb(15 23 42);
    border-color: rgb(71 85 105);
}

.bulk-action-popover-arrow {
    position: absolute;
    top: -0.625rem;
    width: 0;
    height: 0;
    border-left: 0.625rem solid transparent;
    border-right: 0.625rem solid transparent;
    border-bottom: 0.625rem solid rgb(156 163 175);
    z-index: 1;
    filter: none;
    box-shadow: none;
}

.dark .bulk-action-popover-arrow {
    border-bottom-color: rgb(71 85 105);
}

.bulk-action-popover-arrow::after {
    content: '';
    position: absolute;
    top: 1px;
    left: -0.5625rem;
    width: 0;
    height: 0;
    border-left: 0.5625rem solid transparent;
    border-right: 0.5625rem solid transparent;
    border-bottom: 0.5625rem solid white;
    filter: none;
    box-shadow: none;
}

.dark .bulk-action-popover-arrow::after {
    border-bottom-color: rgb(15 23 42);
}

.bulk-action-popover-arrow-right {
    right: var(--arrow-offset, 1.5rem);
}
</style>

