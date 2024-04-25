import React from 'react'
import { normaliseSettings } from '../../utils/normalise'
import { RootElementProps } from '../../utils/types'
import './style.scss'

export default ( props: RootElementProps ) => {

    const encodedSettings = props.el.dataset.settings ?? ''
    let rawSettings
    try {
        rawSettings = JSON.parse( atob( encodedSettings ) )
    } catch( e ) {}

    const settings = normaliseSettings( rawSettings )

    console.log( settings );

    return <div>

    </div>
}
