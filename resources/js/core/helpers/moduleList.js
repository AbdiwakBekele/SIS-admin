export const flattenModules = (modules) => {
    if (!modules) return []

    return modules.reduce((acc, module) => {
        acc.push({
            name: module.name
                .replace(/\./g, " ")
                .replace(/_/g, " ")
                .replace(/\b\w/g, (char) => char.toUpperCase())
                .replace(/\s/g, ""),
            visibility: module.visibility,
        })

        if (module.children) {
            acc.push(...flattenModules(module.children))
        }
        return acc
    }, [])
}
