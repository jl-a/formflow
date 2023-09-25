import { v4 as uuidv4 } from 'uuid'
import { FieldData, FormData } from './types'

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
        fields: [],
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

export {
    normaliseFormData,
    normaliseFieldData,
}
