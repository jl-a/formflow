import submit from './frontend/submit'
import './main.scss'

const init = () => {
    const forms = document.querySelectorAll( '.formflow-form' )
    forms.forEach( form => {
        form.addEventListener( 'submit', submit )
    } )
}

window.addEventListener( 'DOMContentLoaded', init )
