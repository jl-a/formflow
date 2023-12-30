export default ( integrationEl: HTMLElement ) => {

    const integrationButtonWraps = integrationEl.querySelectorAll<HTMLElement>( '.integration-buttons' )

    integrationButtonWraps.forEach( wrap => {
        if ( typeof window?.formflow?.ajax_url !== 'string' ) {
            return
        }

        const spinner = wrap.querySelector<HTMLElement>( '.spinner' )

        const activationButton = wrap.querySelector<HTMLElement>( '.activate' )
        if ( activationButton ) {
            activationButton.addEventListener( 'click', async () => {
                activationButton.style.display = 'none'
                if ( spinner ) {
                    spinner.classList.add( 'is-active' )
                }

                const formData = new FormData()
                formData.append( 'action', 'formflow_activate_integration' )
                formData.append( 'slug', activationButton.dataset.slug )

                await fetch( window.formflow.ajax_url, {
                    method: 'POST',
                    body: formData,
                } )
                location.reload()
            } )
        }

        const deactivationButton = wrap.querySelector<HTMLElement>( '.deactivate' )
        if ( deactivationButton ) {
            deactivationButton.addEventListener( 'click', async () => {
                deactivationButton.style.display = 'none'
                if ( spinner ) {
                    spinner.classList.add( 'is-active' )
                }

                const formData = new FormData()
                formData.append( 'action', 'formflow_deactivate_integration' )
                formData.append( 'slug', deactivationButton.dataset.slug )

                // @ts-ignore
                await fetch( window.formflow.ajax_url, {
                    method: 'POST',
                    body: formData,
                } )
                location.reload()
            } )
        }
    } )
}
