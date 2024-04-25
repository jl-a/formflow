import React from 'react'
import { normaliseFormData } from '../../utils/normalise'
import { RootState } from '../../utils/store/store'
import { RootElementProps } from '../../utils/types'
import { useDispatch, useSelector } from 'react-redux'
import { setDetails } from '../../utils/store/details'
import { setFields } from '../../utils/store/fields'
import TabList from '../../Components/TabList'
import Tabs from '../../Components/Tabs'
import classNames from 'classnames'
import './style.scss'

let initialised = false

/**
 * # FormFLow Edit app
 *
 * Entrypoint for the editor interface, for editing a FormFlow form. Contains
 */
export default ( props: RootElementProps ) => {
    const app = useSelector( ( state: RootState ) => state.app.value )
    const dispatch = useDispatch()

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

    return <>
        <div className={ classNames( 'formflow-tabwrap', { 'saving': app.saving } ) }>
            <TabList />
            <Tabs />
        </div>
        <div className={ classNames( 'spinner', { 'is-active': app.saving } ) } />
    </>
}
