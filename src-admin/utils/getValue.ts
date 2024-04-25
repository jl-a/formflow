/**
 * Ensures than an input is a string. if the input is a number or bool it converts to a
 * string. Otherwise, returns a default value; or if a default value is not provided, returns
 * an empty string.
 * @param value             An unknown input to be tested
 * @param default_value     Default string
 * @returns                 A string
 */
export const getString = ( value: unknown, default_value?: string ) => {
    if (
        typeof value === 'string'
        || typeof value === 'number'
        || typeof value === 'boolean'
    ) {
        return `${ value }`
    }
    if ( typeof default_value === 'string' ) {
        return default_value
    }
    return ''
}

/**
 * Ensures than an input is a number. if the input is a string it attempts to convert to a
 * number. Otherwise, returns a default value; or if a default value is not provided, returns 0
 * @param value             An unknown input to be tested
 * @param default_value     Default number
 * @returns                 A number
 */
export const getNumber = ( value: unknown, default_value?: number ) => {
    if ( typeof value === 'number' ) {
        return value
    }
    if ( typeof value === 'string' ) {
        const return_value = parseFloat( value )
        if ( ! isNaN( return_value ) ) {
            return return_value
        }
    }
    if ( typeof default_value === 'number' ) {
        return default_value
    }
    return 0
}

/**
 * Ensures that an input is an array. Returns a default array if the input is not an array,
 * or if a default value is not provided, returns an empty array.
 * @param value             An unknown input to be tested
 * @param default_value     Default array
 * @returns                 An array
 */
export const getArray = ( value: unknown, default_value?: Array<any> ) => {
    if ( Array.isArray( value ) ) {
        return value
    }
    if ( Array.isArray( default_value ) ) {
        return default_value
    }
    return []
}

/**
 * Takes an input, and if it's an object, it filters it so it only consists of keys and
 * values that are strings. Or returns an empty object if nothing matches or if you pass
 * a non-object to it
 * @param value     An unknown input to be filtered
 * @returns         An object where all keys and values are strings
 */
export const getKeyValueObj = ( value: unknown ) => {
    const result: { [key: string]: string } = {}
    if ( typeof value === 'object' ) {
        Object.entries( value ).forEach( ( [ key, item ] ) => {
            if ( typeof item === 'string' ) {
                result[ key ] = item
            }
        } )
    }
    return result
}
