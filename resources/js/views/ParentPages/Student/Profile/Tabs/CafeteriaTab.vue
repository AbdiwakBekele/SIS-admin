<template>
    <!-- Meal Plan -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong>Meal Plan</strong> </p>
        <hr class="mx-4">

        <div class="grid grid-cols-5 my-2">

            <!-- Monday -->
            <div class="col-span-1">
                <span class="text-sm">Monday</span><br>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Breakfast</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Oatmeal with banana and
                        peanut butter</span>
                </div>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Lunch</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Rice with
                        stir-fried</span>
                </div>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Snack</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Yogurt with
                        granola</span>
                </div>

            </div>

            <!-- Tuesday -->
            <div class="col-span-1">
                <span class="text-sm">Tuesday</span><br>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Breakfast</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Oatmeal with banana and
                        peanut butter</span>
                </div>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Lunch</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Rice with
                        stir-fried</span>
                </div>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Snack</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Yogurt with
                        granola</span>
                </div>

            </div>

            <!-- Wednesday -->
            <div class="col-span-1">
                <span class="text-sm">Wednesday</span><br>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Breakfast</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Oatmeal with banana and
                        peanut butter</span>
                </div>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Lunch</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Rice with
                        stir-fried</span>
                </div>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Snack</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Yogurt with
                        granola</span>
                </div>

            </div>

            <!-- Thursday -->
            <div class="col-span-1">
                <span class="text-sm">Thursday</span><br>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Breakfast</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Oatmeal with banana and
                        peanut butter</span>
                </div>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Lunch</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Rice with
                        stir-fried</span>
                </div>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Snack</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Yogurt with
                        granola</span>
                </div>

            </div>

            <!-- Friday -->
            <div class="col-span-1">
                <span class="text-sm">Friday</span><br>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Breakfast</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Oatmeal with banana and
                        peanut butter</span>
                </div>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Lunch</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Rice with
                        stir-fried</span>
                </div>

                <div class="m-2 bg-gray-100 border border-gray-300 rounded-lg text-center p-1">
                    <p class="text-sm"><strong>Snack</strong></p>
                    <span class="text-gray-600" style="font-size:12px">Yogurt with
                        granola</span>
                </div>

            </div>

        </div>

    </div>

    <!-- Allergies -->
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-left mt-2">
        <p class="text-sm text-left mx-4"> <strong>Allergies</strong> </p>
        <hr class="mx-4">

        <div class="m-2 p-2 bg-gray-100 border border-gray-300 rounded-lg">
            <span class="text-gray-600 text-sm">
                - Eggs <br>
                - Soya <br>
                - Fish <br>
            </span>

        </div>

    </div>

</template>

<script setup>
defineProps({
    student: Object
})
</script>