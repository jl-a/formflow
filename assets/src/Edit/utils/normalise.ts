import { v4 as uuidv4 } from 'uuid'

type FormData = {
    details: {
        id: string
        title: string
    }
    settings: {}
    fields: Array<FieldData>
}

type FieldData = {
    id: string
    title: string
    type: string
}

const getString = ( value: unknown, default_value?: string ) => {
    if (
        typeof value === 'string'
        || typeof value === 'number'
        || typeof value === 'boolean'
    ) {
        return `${ value }`
    } else if ( typeof default_value === 'string' ) {
        return default_value
    } else {
        return ''
    }
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
        title: getString( input?.title ),
        type: getString( input?.type, 'text' ),
    }

    return model
}

export {
    normaliseFormData,
    normaliseFieldData,
}
