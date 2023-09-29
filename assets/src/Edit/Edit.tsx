import React from 'react'
import { normaliseFormData } from '../utils/normalise'
import { RootState } from '../utils/store'
import { RootElementProps } from '../utils/types'
import FieldList from '../Components/FieldList'
import Button from '../Components/Button'
import { useDispatch, useSelector } from 'react-redux'
import { initialise  } from '../utils/fields'
import './style.scss'

let initialised = false

const Edit = ( props: RootElementProps ) => {
    const fields = useSelector( ( state: RootState ) => state.fields.value )

    if ( ! initialised ) {
        const formId = props.el.dataset.form_id ?? 'new'
        const encodedForm = props.el.dataset.form ?? ''
        const dispatch = useDispatch()

        let rawFormData
        if ( formId !== 'new' ) {
            try {
                rawFormData = JSON.parse( atob( encodedForm ) )
            } catch( e ) {}
        }
        const formData = normaliseFormData( formId, rawFormData )

        initialised = true
        dispatch( initialise( formData.fields ) )
    }

    const save = () => {
        console.log( fields );
    }

    return <>
        <FieldList parent='root' />
        <Button
            className='formflow-save-button'
            onClick={ save }
        >
            Save Form
        </Button>
    </>
}

export default Edit
