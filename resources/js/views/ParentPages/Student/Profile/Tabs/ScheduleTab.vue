<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong> Schedule Info </strong> </p>
        <hr class="mx-4">

        <div v-if="false" class="grid grid-cols-5 my-2">
            <div class="col-span-1">
                <span class="text-sm">MONDAY</span>
            </div>

            <div class="col-span-1">
                <span class="text-sm">TUESDAY</span>
            </div>

            <div class="col-span-1">
                <span class="text-sm">WEDNESDAY</span>
            </div>
            <div class="col-span-1">
                <span class="text-sm">THURSDAY</span>
            </div>
            <div class="col-span-1">
                <span class="text-sm">FRIDAY</span>
            </div>
        </div>

        <div v-if="false" class="grid grid-cols-5 text-left">

            <div class="col-span-1 m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-3">
                <p class="text-sm"><strong>Maths</strong></p>
                <span class="text-gray-600" style="font-size:12px">Location: Rama 104</span>
                <br>
                <span class="text-gray-600" style="font-size:12px">Teacher: Sofi
                    Joseph</span><br>
                <span class="text-gray-600" style="font-size:12px">09:00 - 09:45</span>
            </div>

            <div class="col-span-1 m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-3">
                <p class="text-sm"><strong>English</strong></p>
                <span class="text-gray-600" style="font-size:12px">Location: Rama 104</span>
                <br>
                <span class="text-gray-600" style="font-size:12px">Teacher: Sofi
                    Joseph</span><br>
                <span class="text-gray-600" style="font-size:12px">10:00 - 10:45</span>
            </div>

            <div class="col-span-1 m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-3">
                <p class="text-sm"><strong>Biology</strong></p>
                <span class="text-gray-600" style="font-size:12px">Location: Rama 104</span>
                <br>
                <span class="text-gray-600" style="font-size:12px">Teacher: Sofi
                    Joseph</span><br>
                <span class="text-gray-600" style="font-size:12px">10:45 - 11:45</span>
            </div>

            <div class="col-span-1 m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-3">
                <p class="text-sm"><strong>Chemistry</strong></p>
                <span class="text-gray-600" style="font-size:12px">Location: Rama 104</span>
                <br>
                <span class="text-gray-600" style="font-size:12px">Teacher: Sofi
                    Joseph</span><br>
                <span class="text-gray-600" style="font-size:12px">12:00 - 12:45</span>
            </div>

            <div class="col-span-1 m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-3">
                <p class="text-sm"><strong>Physics</strong></p>
                <span class="text-gray-600" style="font-size:12px">Location: Rama 104</span>
                <br>
                <span class="text-gray-600" style="font-size:12px">Teacher: Sofi
                    Joseph</span><br>
                <span class="text-gray-600" style="font-size:12px">13:00 - 13:45</span>
            </div>
        </div>

        <div v-if="true" class="pr-6 py-5 text-center">
            <i class="fa-solid fa-clock text-2xl mb-2 text-gray-500"></i>
            <p class="text-gray-500">No Schedule Yet</p>
        </div>
    </div>
</template>

<script setup>
defineProps({
    student: Object
})
</script>