import React from 'react'
import ReactDOM from 'react-dom'
import Edit from './Edit/Edit'
import './admin.scss'

const init = () => {
    const edit_el = document.getElementById( 'formflow-edit' )

    if ( edit_el ) {
        ReactDOM.render( <Edit el={ edit_el } />, edit_el )
    }
}

window.addEventListener( 'DOMContentLoaded', init )
