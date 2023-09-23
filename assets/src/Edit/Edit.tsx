import React from 'react'
import { normaliseFormData } from './utils/normalise'
import './Edit.scss'

interface RootElementProps {
    el: HTMLElement,
}

const Edit = ( props: RootElementProps ) => {
    const formId = props.el.dataset.form_id ?? 'new'
    const encodedForm = props.el.dataset.form ?? ''

    let rawFormData
    if ( formId !== 'new' ) {
        try {
            rawFormData = JSON.parse( atob( encodedForm ) )
        } catch( e ) {}
    }

    const formData = normaliseFormData( formId, rawFormData )

    return <h1>Hello</h1>
}

export default Edit
