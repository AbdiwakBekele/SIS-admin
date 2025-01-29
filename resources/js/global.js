import katex from "katex"
import "katex/dist/katex.min.css"

window.katex = katex

document.addEventListener("DOMContentLoaded", () => {
    if (window.katex) {
        document.querySelectorAll(".math").forEach((el) => {
            window.katex.render(el.dataset.katex, el, {
                throwOnError: false,
            })
        })
    }
})
