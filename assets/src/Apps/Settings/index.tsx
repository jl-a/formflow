import React from 'react'
import { RootElementProps } from '../../utils/types'
import './style.scss'

export default ( props: RootElementProps ) => {

    const encodedSettings = props.el.dataset.settings ?? ''
    let settings
    try {
        settings = JSON.parse( atob( encodedSettings ) )
    } catch( e ) {}

    return <div>

    </div>
}
