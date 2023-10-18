const getElements: ( form: HTMLElement ) => { [key: string]: HTMLElement | null } = ( form ) => {
    return {
        loading: form.querySelector<HTMLElement>( '.formflow-loading' ),
        fields: form.querySelector<HTMLElement>( '.formflow-wrap' ),
        message: form.querySelector<HTMLElement>( '.formflow-message' ),
    }
}

const show = ( form: HTMLElement, elementId: 'loading' | 'fields' | 'message' ) => {
    const elements = getElements( form )

    Object.keys( elements ).forEach( elementKey => {
        if ( elements[ elementKey ] instanceof HTMLElement ) {
            if ( elementKey === elementId ) {
                elements[ elementKey ].style.visibility = 'visible'
                window.requestAnimationFrame( () => window.requestAnimationFrame( // repaint occurrs after two animationframes
                    () =>  elements[ elementKey ].style.opacity = '1'
                ) )
            } else {
                elements[ elementKey ].style.opacity = '0'
                window.setTimeout( () => elements[ elementKey ].style.visibility = 'hidden', 110 ) // give it an extra 10ms to ensure the animation is done
            }
        }
    } )
}

export {
    show,
}
