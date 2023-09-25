import React from 'react'
import ReactDOM from 'react-dom/client'
import Edit from './Edit/Edit'
import { store } from './utils/store'
import { Provider } from 'react-redux'
import './admin.scss'

const init = () => {
    const edit_el = document.getElementById( 'formflow-edit' )

    if ( edit_el ) {
        const root = ReactDOM.createRoot( edit_el )
        root.render(
            <Provider store={store}>
                <Edit el={ edit_el } />
            </Provider>
        )
    }
}

window.addEventListener( 'DOMContentLoaded', init )
