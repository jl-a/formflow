import React from 'react'
import { normaliseFormData } from '../../utils/normalise'
import { RootState } from '../../utils/store/store'
import { RootElementProps } from '../../utils/types'
import Button from '../../Components/Button'
import { useDispatch, useSelector } from 'react-redux'
import { setDetails } from '../../utils/store/details'
import { setFields } from '../../utils/store/fields'
import { updateApp } from '../../utils/store/app'
import save from './save'
import './style.scss'
import TabList from '../../Components/TabList'
import Tabs from '../../Components/Tabs'
import classNames from 'classnames'

let initialised = false

export default ( props: RootElementProps ) => {
    const app = useSelector( ( state: RootState ) => state.app.value )
    const details = useSelector( ( state: RootState ) => state.details.value )
    const fields = useSelector( ( state: RootState ) => state.fields.value )
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

    const onSaveClick = async () => {
        dispatch( updateApp( { saving: true } ) )
        await save( details, fields )
        dispatch( updateApp( { saving: false } ) )
    }

    return <>
        <div className={ classNames( 'formflow-tabwrap', { 'saving': app.saving } ) }>
            <TabList />
            <Tabs />
        </div>

        <Button
            className="formflow-save-button"
            disabled={ !! app.saving }
            onClick={ onSaveClick }
        >
            Save Form
        </Button>
    </>
}
