import { DetailsData, FieldData } from './types'

const save = async (
    details: DetailsData,
    fields: Array<FieldData>
) => {
    const formData = new FormData()
    formData.append( 'action', 'formflow_save_form' )
    formData.append( 'details', JSON.stringify( details ) )
    formData.append( 'fields', JSON.stringify( fields ) )

    // @ts-ignore
    const rawResponse = await fetch( window?.formflow?.ajax_url, {
        method: 'POST',
        body: formData,
    } );
    const response = await rawResponse.json()
    if ( response.success && response.redirect && typeof response.redirect === 'string' ) {
        window.location = response.redirect
    }
}

export default save
