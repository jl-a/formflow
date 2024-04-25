import React from 'react'
import ReactDOM from 'react-dom/client'
import Edit from './Apps/Edit'
import integrations from './Apps/Integrations'
import Settings from './Apps/Settings'
import { store } from './utils/store/store'
import { Provider } from 'react-redux'
import './admin.scss'

declare global {
    interface Window { formflow: {
        ajax_url: string
    } }
}

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

    const integrationEl = document.getElementById( 'formflow-integration-list' )
    if ( integrationEl ) {
        integrations( integrationEl )
    }

    const settingsEl = document.getElementById( 'formflow-settings' )
    if ( settingsEl ) {
        const root = ReactDOM.createRoot( settingsEl )
        root.render(
            <Provider store={store}>
                <Settings el={ settingsEl } />
            </Provider>
        )
    }
}

window.addEventListener( 'DOMContentLoaded', init )
