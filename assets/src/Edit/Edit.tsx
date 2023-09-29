import React from 'react'
import { normaliseFormData } from '../utils/normalise'
import { RootState } from '../utils/store/store'
import { RootElementProps } from '../utils/types'
import FieldList from '../Components/FieldList'
import Button from '../Components/Button'
import { useDispatch, useSelector } from 'react-redux'
import { initialise  } from '../utils/store/fields'
import './style.scss'

let initialised = false

const Edit = ( props: RootElementProps ) => {
    const fields = useSelector( ( state: RootState ) => state.fields.value )

    if ( ! initialised ) {
        // If not initialised, attempt to read and parse the form data that's
        // attached as a data attribute on the HTML element, and dispatch to
        // the store
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

        initialised = true // set flag, otherwise it will keep re-rendering every time it dispatches the data
        dispatch( initialise( formData.fields ) )
    }

    /**
     *
     */
    const save = async () => {
        console.log( fields );

        const formData = new FormData()
        formData.append( 'action', 'formflow_save_form' )
        formData.append( 'fields', JSON.stringify( fields ) )

        const data = {
            action: 'formflow_save_form',
            fields,
        }

        // @ts-ignore
        const rawResponse = await fetch( window?.formflow?.ajax_url, {
            method: 'POST',
            body: formData,
        });
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
