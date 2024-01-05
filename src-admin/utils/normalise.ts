import { v4 as uuidv4 } from 'uuid'
import { FieldData, FormData, Settings, IntegrationSetting } from './types'

/**
 * Ensures than an input is a string. if the input is a number or bool it converts to a
 * string. Otherwise, returns a default value; or if a default value is not provided, returns
 * an empty string.
 * @param value             An unknown input to be tested
 * @param default_value     Default string
 * @returns                 A string
 */
const getString = ( value: unknown, default_value?: string ) => {
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
const getNumber = ( value: unknown, default_value?: number ) => {
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
const getArray = ( value: unknown, default_value?: Array<any> ) => {
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
const getKeyValueObj = ( value: unknown ) => {
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

/**
 * Takes raw input that could be anything, and parses it into the Settings type. See the
 * type for detailed information.
 * @param rawInput
 * @returns
 */
const normaliseSettings = ( rawInput: unknown ) => {
    // Ensure the input is an object. If it's not an object (eg a null or something)
    // we'll force an empty object so edge cases where we try to access non-existant
    // properties won't error
    const input = typeof rawInput === 'object' ? rawInput as any : {}

    const model: Settings = {
        settings: {},
        integrations: {},
    }

    if ( typeof input?.integrations === 'object' ) {
        Object.entries( input.integrations ).forEach( ( [ id, integration ] ) => {
            model.integrations[ id ] = {}; // initialise the integration settings with an empty object that will be populated later

            ( Object.entries( integration ) as Array<[string, IntegrationSetting]> )
                .forEach( ( [ key, setting ] ) => {
                    if ( typeof setting?.id === 'string' && typeof setting?.title === 'string' ) { // minimum required keys
                        model.integrations[ id ][ key ] = {
                            ...setting,
                            value: getString( setting?.value ),
                            type: getString( setting?.type, 'text' ),
                            options: getKeyValueObj( setting?.options ),
                            conditional: getArray( setting?.conditional ),
                        }
                    }
                } )
        } )
    }

    return model
}

const normaliseFormData = ( formId: string, rawInput: unknown ) => {
    // Ensure the input is an object. If it's not an object (eg a null or something)
    // we'll force an empty object so edge cases where we try to access non-existant
    // properties won't error
    const input = typeof rawInput === 'object' ? rawInput as any : {}

    // Populate the default model
    const model: FormData = {
        details: {
            id: formId,
            title: getString( input?.details?.title )
        },
        settings: {},
        fields: input?.fields?.map( ( field: unknown ) => normaliseFieldData( field ) ) || [],
    }

    return model
}

const normaliseFieldData = ( rawInput: unknown ) => {
    // Ensure the input is an object. If it's not an object (eg a null or something)
    // we'll force an empty object so edge cases where we try to access non-existant
    // properties won't error
    const input = typeof rawInput === 'object' ? rawInput as any : {}

    const model: FieldData = {
        id: getString( input?.id, uuidv4() ),
        parent: getString( input?.parent, 'root' ),
        title: getString( input?.title ),
        type: getString( input?.type, 'text' ),
        position: getNumber( input?.position, -1 )
    }

    return model
}

const normalisePositions = ( allFields: Array<FieldData>, parentId: string ) => {
    const fields = allFields
        .filter( field => field.parent === parentId )
        .sort( ( a, b ) => { // Sorts by the position property
            if ( a.position > b.position ) {
                return 1
            }
            if ( a.position < b.position ) {
                return -1
            }
            return 0
        } )

    for ( let i = 0; i < fields.length; i++ ) {
        fields[ i ].position = i
    }

    return fields
}

const initialForm = normaliseFormData( null, null )

export {
    normaliseSettings,
    normaliseFormData,
    normaliseFieldData,
    normalisePositions,
    initialForm,
}
