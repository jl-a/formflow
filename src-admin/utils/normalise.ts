import { v4 as uuidv4 } from 'uuid'
import { FieldData, FormData, Settings, IntegrationSetting } from './types'
import { getString, getNumber, getArray, getKeyValueObj } from './getValue'

/**
 * Takes raw input that could be anything, and parses it into the Settings type. See the
 * type for detailed information.
 * @param rawInput
 * @returns
 */
export const normaliseSettings = ( rawInput: unknown ) => {
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
            if ( typeof integration !== 'object' ) {
                return
            }
            model.integrations[ id ] = {}; // initialise the integration settings with an empty object that will be populated later

            ( Object.entries( integration ) as Array<[string, IntegrationSetting]> )
                .forEach( ( [ key, setting ] ) => {
                    if (
                        typeof setting === 'object'
                        && typeof setting?.id === 'string'
                        && typeof setting?.title === 'string' // absolute minimum required keys are id and title. We can extrapolate the rest if not present
                    ) {
                        model.integrations[ id ][ key ] = {
                            ...setting,
                            id: key, // guarantees the ID is the same as the object's key
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

/**
 * Takes raw input that could be anything, and parses it into the FormData type. See the
 * type for detailed information.
 * @param formId        The ID of the form to parse
 * @param rawInput      The raw data to parse
 * @returns
 */
export const normaliseFormData = ( formId: string, rawInput: unknown ) => {
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

export const normaliseFieldData = ( rawInput: unknown ) => {
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

export const normalisePositions = ( allFields: Array<FieldData>, parentId: string ) => {
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

export const initialForm = normaliseFormData( null, null )
