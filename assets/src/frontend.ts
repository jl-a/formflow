import submit from './frontend/submit'
import './frontend.scss'

const init = () => {
    const forms = document.querySelectorAll( '.formflow-form' )
    forms.forEach( form => {
        form.addEventListener( 'submit', submit )
    } )
}

window.addEventListener( 'DOMContentLoaded', init )
