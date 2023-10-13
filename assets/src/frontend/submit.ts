const submit = async ( e: SubmitEvent ) => {
    e.preventDefault()
    const form = e.target as HTMLFormElement

    const loading = form.querySelector<HTMLElement>( '.formflow-loading' )
    if ( loading ) {
        loading.style.display = 'flex'
        window.requestAnimationFrame( () => window.requestAnimationFrame( // repaint occurrs after two animationframes
            () => loading.style.opacity = '1'
        ) )
    }

    const data: Array<{ id: string, value: any }> = []
    const fields = form.querySelectorAll( '.formflow-field' )
    fields.forEach( field => {
        const input = field.querySelector( '.formflow-input' )
        if (
            input.tagName !== 'INPUT'
            && input.tagName !== 'SELECT'
            && input.tagName !== 'TEXTAREA'
        ) {
            return
        }
        const id = field.id.replace( 'formflow-field-', '' )
        const value = ( input as any ).value

        data.push( {
            id,
            value,
        } )
    } )

    const formData = new FormData()
    formData.append( 'action', 'formflow_submit_form' )
    formData.append( 'data', JSON.stringify( data ) )

    // @ts-ignore
    const rawResponse = await fetch( window?.formflow?.ajax_url, {
        method: 'POST',
        body: formData,
    } );
    const response = await rawResponse.json()

    console.log( data );

    return false
}

export default submit
