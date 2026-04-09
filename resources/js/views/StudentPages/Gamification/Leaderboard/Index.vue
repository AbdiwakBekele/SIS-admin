<template>

    <body
        style="font-family: Arial, sans-serif; background-color: #f4f4f4; text-align: center; margin: 0; padding: 20px;">
        <h1 style="font-size: 30px; text-align: start;"> <strong> Leaderboard </strong> </h1>
        <div
            style="width: 80%; max-width: 800px; margin: 10px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <h2 style="color: #333;">🏆 Student Leaderboard</h2>
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                <thead>
                    <tr style="background-color: #007bff; color: white;">
                        <th style="padding: 10px; border-bottom: 1px solid #ddd;">Student Name</th>
                        <th style="padding: 10px; border-bottom: 1px solid #ddd;">Points</th>
                        <th style="padding: 10px; border-bottom: 1px solid #ddd;">Badges</th>
                        <th style="padding: 10px; border-bottom: 1px solid #ddd;">Streak Stars</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Alice Johnson</td>
                        <td style="color:green;">250</td>
                        <td>🏅 Gold Achiever</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>Michael Smith</td>
                        <td style="color:green;">200</td>
                        <td>🥈 Silver Explorer</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>Emma Brown</td>
                        <td style="color:green;">180</td>
                        <td>🥉 Bronze Star</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>Oliver Davis</td>
                        <td style="color:green;">160</td>
                        <td>⭐ Rising Star</td>
                        <td>3</td>
                    </tr>
                    <tr>
                        <td>Sophia Martinez</td>
                        <td style="color:green;">140</td>
                        <td>🔥 New Challenger</td>
                        <td>2</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>

</template>

<script>
export default {
    name: "AcademicDivisionList",
}
</script>

<script setup>
import { ref, reactive, inject } from "vue"
import { useRouter } from "vue-router"
import { perform } from "@core/helpers/action"

const router = useRouter()

const emitter = inject("emitter")

let userActions = ["filter"]
if (perform("division:create")) {
    userActions.unshift("create")
}

let dropdownActions = []
if (perform("division:export")) {
    dropdownActions = ["print", "pdf", "excel"]
}

const initUrl = "schoolSetup/division/"
const showFilter = ref(false)
const showReorderModal = ref(false)

const divisions = reactive({})

const setItems = (data) => {
    Object.assign(divisions, data)
}
</script>
