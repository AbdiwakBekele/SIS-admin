import hljs from "highlight.js/lib/core"

// import hljsBlade from 'highlightjs-blade'
// import hljsCurl from 'highlightjs-curl'

import bash from "highlight.js/lib/languages/bash"
import css from "highlight.js/lib/languages/css"
import dart from "highlight.js/lib/languages/dart"
import diff from "highlight.js/lib/languages/diff"
import dns from "highlight.js/lib/languages/dns"
import docker from "highlight.js/lib/languages/dockerfile"
import excel from "highlight.js/lib/languages/excel"
import http from "highlight.js/lib/languages/http"
import handlebars from "highlight.js/lib/languages/handlebars"
import json from "highlight.js/lib/languages/json"
import javascript from "highlight.js/lib/languages/javascript"
import markdown from "highlight.js/lib/languages/markdown"
import nginx from "highlight.js/lib/languages/nginx"
import php from "highlight.js/lib/languages/php"
import python from "highlight.js/lib/languages/python"
import scss from "highlight.js/lib/languages/scss"
import sql from "highlight.js/lib/languages/sql"
import shell from "highlight.js/lib/languages/shell"
import typescript from "highlight.js/lib/languages/typescript"
import xml from "highlight.js/lib/languages/xml"

hljs.registerLanguage("bash", bash)
// hljs.registerLanguage('blade', hljsBlade)
hljs.registerLanguage("css", css)
// hljs.registerLanguage('curl', hljsCurl)
hljs.registerLanguage("dart", dart)
hljs.registerLanguage("diff", diff)
hljs.registerLanguage("dns", dns)
hljs.registerLanguage("docker", docker)
hljs.registerLanguage("excel", excel)
hljs.registerLanguage("http", http)
hljs.registerLanguage("handlebars", handlebars)
hljs.registerLanguage("json", json)
hljs.registerLanguage("javascript", javascript)
hljs.registerLanguage("markdown", markdown)
hljs.registerLanguage("nginx", nginx)
hljs.registerLanguage("php", php)
hljs.registerLanguage("python", python)
hljs.registerLanguage("scss", scss)
hljs.registerLanguage("sql", sql)
hljs.registerLanguage("shell", shell)
hljs.registerLanguage("typescript", typescript)
hljs.registerLanguage("xml", xml)

window.hljs = hljs

export default hljs
