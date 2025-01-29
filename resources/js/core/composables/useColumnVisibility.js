import Cookies from "js-cookie"

export function useColumnVisibility(moduleName) {
    const COOKIE_KEY = `${moduleName}_columns`

    const applyVisibility = (headers) => {
        const savedVisibility = Cookies.get(COOKIE_KEY)
        if (savedVisibility) {
            const visibilityMap = JSON.parse(savedVisibility)
            return headers.map((header) => ({
                ...header,
                visibility: visibilityMap.includes(header.key),
            }))
        }
        return headers
    }

    const saveVisibility = (visibleColumns) => {
        Cookies.set(COOKIE_KEY, JSON.stringify(visibleColumns), { expires: 1 })
    }

    return {
        applyVisibility,
        saveVisibility,
    }
}
