import React from 'react'
import ReactDOM from 'react-dom/client'
import Edit from './Apps/Edit'
import { store } from './utils/store/store'
import { Provider } from 'react-redux'
import './admin.scss'

const init = () => {
    const editEl = document.getElementById( 'formflow-edit' )

    if ( editEl ) {
        const root = ReactDOM.createRoot( editEl )
        root.render(
            <Provider store={store}>
                <Edit el={ editEl } />
            </Provider>
        )
    }
}

window.addEventListener( 'DOMContentLoaded', init )
