import React from 'react'
import { normaliseFormData } from '../../utils/normalise'
import { RootState } from '../../utils/store/store'
import { RootElementProps } from '../../utils/types'
import FieldList from '../../Components/FieldList'
import Button from '../../Components/Button'
import { useDispatch, useSelector } from 'react-redux'
import { setDetails } from '../../utils/store/details'
import { setFields } from '../../utils/store/fields'
import './style.scss'

let initialised = false

const Edit = ( props: RootElementProps ) => {
    const details = useSelector( ( state: RootState ) => state.details.value )
    const fields = useSelector( ( state: RootState ) => state.fields.value )
    const dispatch = useDispatch()
    const pageTitle = document.getElementById( 'formflow-title' )

    if ( ! initialised ) {
        // If not initialised, attempt to read and parse the form data that's
        // attached as a data attribute on the HTML element, and dispatch to
        // the store
        const formId = props.el.dataset.form_id ?? 'new'
        const encodedForm = props.el.dataset.form ?? ''

        let rawFormData
        if ( formId !== 'new' ) {
            try {
                rawFormData = JSON.parse( atob( encodedForm ) )
            } catch( e ) {}
        }
        const formData = normaliseFormData( formId, rawFormData )

        initialised = true // set flag, otherwise it will keep re-rendering every time it dispatches the data
        dispatch( setDetails( formData.details ) )
        dispatch( setFields( formData.fields ) )
    }

    const updateTitle = ( title: any ) => {
        dispatch( setDetails( {
            ...details,
            title
        } ) )
        if ( pageTitle && details.id !== 'new' ) {
            pageTitle.innerHTML = `Edit ${ title } - Form Flow`
        }
    }

    /**
     *
     */
    const save = async () => {
        const formData = new FormData()
        formData.append( 'action', 'formflow_save_form' )
        formData.append( 'details', JSON.stringify( details ) )
        formData.append( 'fields', JSON.stringify( fields ) )

        // @ts-ignore
        const rawResponse = await fetch( window?.formflow?.ajax_url, {
            method: 'POST',
            body: formData,
        } );
        const response = await rawResponse.json()
        if ( response.success && response.redirect && typeof response.redirect === 'string' ) {
            window.location = response.redirect
        }
    }

    return <>
        <div style={ { marginBottom: '50px' } }>
            <input
                type='text'
                style={ { width: '100%', padding: '5px' } }
                value={ details.title }
                onChange={ e => updateTitle( e.target.value ) }
            />
        </div>
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
