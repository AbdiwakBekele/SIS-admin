<template>
    <div class="bg-white rounded-lg border-r pr-6 py-5 text-center mt-2">
        <p class="text-sm text-left mx-4"> <strong>Library</strong> </p>
        <hr class="mx-4">

        <div class="grid grid-cols-3 text-left gap-2">

            <div class="col-span-1 m-2 border border-gray-300 rounded-lg text-center p-3"
                v-for="book in studentData.books" :key="book.name">
                <img class="mx-auto w-[100px]" :src="book.url" alt="" />
                <p class="text-sm"><strong>{{ book.name }}</strong></p>
                <hr>

                <div class="gird grid-cols-2 ">
                    <span class="col-span-1 text-gray-600 text-sm">Book taken on
                        <br>
                        {{ new Date(book.bookTakenOn).toLocaleDateString('en-US', {
                            year:
                                'numeric', month: 'long', day: '2-digit'
                        }) }}
                    </span> <br>
                    <span class="col-span-1 text-gray-600 text-sm">Last Date <br>
                        {{ new Date(book.lastDate).toLocaleDateString('en-US', {
                            year:
                                'numeric', month: 'long', day: '2-digit'
                        }) }}</span>
                </div>
            </div>

        </div>
    </div>

</template>

<script setup>
defineProps({
    student: Object
})
</script>