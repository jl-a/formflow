import React from 'react'
import { normaliseFormData } from '../utils/normalise'
import { RootElementProps } from '../utils/types'
import FieldList from '../Components/FieldList'
import { useDispatch } from 'react-redux'
import { initialise  } from '../utils/fields'
import './style.scss'

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
    const dispatch = useDispatch()
    dispatch( initialise( formData.fields ) )

    return <FieldList parent='root' />
}

export default Edit
