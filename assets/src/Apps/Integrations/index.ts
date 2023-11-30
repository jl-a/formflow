export default ( integrationEl: HTMLElement ) => {
    const integrationButtonWraps = integrationEl.querySelectorAll<HTMLElement>( '.integration-buttons' )
    integrationButtonWraps.forEach( wrap => {
        const activationButton = wrap.querySelector<HTMLElement>( '.activate' );
        const deactivationButton = wrap.querySelector<HTMLElement>( '.deactivate' );
        const spinner = wrap.querySelector<HTMLElement>( '.spinner' );

        if ( activationButton ) {
            activationButton.addEventListener( 'click', async () => {
                activationButton.style.display = 'none'
                if ( spinner ) {
                    spinner.classList.add( 'is-active' )
                }

                const formData = new FormData()
                formData.append( 'action', 'formflow_activate_integration' )
                formData.append( 'slug', activationButton.dataset.slug )

                // @ts-ignore
                await fetch( window?.formflow?.ajax_url, {
                    method: 'POST',
                    body: formData,
                } )
                location.reload()
            } )
        }

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
                await fetch( window?.formflow?.ajax_url, {
                    method: 'POST',
                    body: formData,
                } )
                location.reload()
            } )
        }
    } )
}
