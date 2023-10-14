import { show_loading, hide_loading } from './loading'

const submit = async ( e: SubmitEvent ) => {
    e.preventDefault()
    const form = e.target as HTMLFormElement
    const formId = form.dataset.formId
    if ( ! formId ) {
        return
    }

    show_loading( form )

    const data: Array<{ id: string, value: any }> = []
    const fields = form.querySelectorAll( '.formflow-field' )
    fields.forEach( field => {
        const input = field.querySelector<HTMLElement>( '.formflow-input' )
        if (
            input.tagName !== 'INPUT'
            && input.tagName !== 'SELECT'
            && input.tagName !== 'TEXTAREA'
        ) {
            return
        }

        const id = input.dataset.fieldId
        if ( ! id ) {
            return
        }

        const value = ( input as HTMLInputElement ).value

        data.push( {
            id,
            value,
        } )
    } )

    const formData = new FormData()
    formData.append( 'action', 'formflow_submit_form' )
    formData.append( 'form_id', formId )
    formData.append( 'fields', JSON.stringify( data ) )

    // @ts-ignore
    const rawResponse = await fetch( window?.formflow?.ajax_url, {
        method: 'POST',
        body: formData,
    } );
    let response = null
    try {
        response = await rawResponse.json()
    } catch ( e ) {}

    console.log( response );

    hide_loading( form )

    return false
}

export default submit
