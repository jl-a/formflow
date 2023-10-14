const show_loading = async ( form: HTMLElement ) => {
    const loadingEl = form.querySelector<HTMLElement>( '.formflow-loading' )
    const fieldsEl = form.querySelector<HTMLElement>( '.formflow-fields' )

    if ( loadingEl && fieldsEl ) {
        loadingEl.style.display = 'flex'
        fieldsEl.style.opacity = '0'
        window.requestAnimationFrame( () => window.requestAnimationFrame( // repaint occurrs after two animationframes
            () => loadingEl.style.opacity = '1'
        ) )
    }
}

const hide_loading = async ( form: HTMLElement ) => {
    const loadingEl = form.querySelector<HTMLElement>( '.formflow-loading' )
    const fieldsEl = form.querySelector<HTMLElement>( '.formflow-fields' )

    if ( loadingEl && fieldsEl ) {

        loadingEl.style.opacity = '0'
        fieldsEl.style.opacity = '1'
        window.setTimeout( () => loadingEl.style.display = 'none', 100 )
    }
}

export {
    show_loading,
    hide_loading,
}
