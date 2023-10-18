import { show } from './screens'

export type ResponseData = {
    formId: number | null
    success?: boolean
    validationErrors?: Array<{
        id: string
        error: string
    }>
    errorMessage?: string
    successMessage?: string
}

const response = ( data: ResponseData ) => {

    const form = document.querySelector<HTMLFormElement>( `#formflow-${ data.formId }` )
    if ( ! form ) {
        return
    }

    if ( ! data.success ) {
        show( form, 'fields' )
    } else {
        const message = data.successMessage || 'The form has been successfully submitted.'
        const messageEl = form.querySelector( '.formflow-message' )

        if ( messageEl ) {
            messageEl.innerHTML = message
            show( form, 'message' )
        } else {
            show( form, 'fields' )
        }
    }

}

export default response
