import React, { useState } from 'react'
import { RootState } from '../../../utils/store/store'
import { useDispatch, useSelector } from 'react-redux'
import { updateDetail } from '../../../utils/store/details'
import Button from '../../Button'
import classNames from 'classnames'
import './style.scss';

export default () => {
    const details = useSelector( ( state: RootState ) => state.details.value )
    const dispatch = useDispatch()
    const pageTitle = document.getElementById( 'formflow-title' )

    const [ copyMessage, setCopyMessage ] = useState( false )
    const [ unlockDelete, setUnlockDelete ] = useState( false )

    const shortcode = `[formflow-form id="${ details.id }"]` // fixed string with the WordPress shortcode

    /**
     * Runs on every input from the Form Title text input. Modifies the page title, so that when you type
     * in a new title it's automatically shown at the top of the page. Also dispatches the new title to
     * the form model, so that saving the form will include the new title.
     *
     * @param title The input value
     */
    const updateTitle = ( title: any ) => {
        dispatch( updateDetail( { title } ) )
        if ( pageTitle && details.id !== 'new' ) {
            pageTitle.innerHTML = `Edit ${ title } - Form Flow`
        }
    }

    /**
     * Runs when the Copy shortcode button is clicked. Writes the shortcode string to the clipboard and
     * shows a little confirmation message for a couple of seconds.
     */
    const copyShortcode = () => {
        navigator.clipboard.writeText( shortcode )
        setCopyMessage( true )
        setTimeout( () => setCopyMessage( false ), 2000 )
    }

    /**
     * Runs on every input from the Delete input. If the input matches the string `DELETE` then it unlocks
     * the delete button via the unlockDelete state.
     *
     * @param input The input value
     */
    const checkUnlockDelete = ( input: any ) => {
        if ( input === 'DELETE' ) {
            setUnlockDelete( true )
        } else {
            setUnlockDelete( false )
        }
    }

    /**
     * Runs when the button to delete form is clicked. Sends a delete request to WordPress, and redirects to
     * the form listing page once completed.
     */
    const deleteForm = () => {
        if ( ! unlockDelete ) {
            return
        }
        console.log('todo');
    }

    return <div className='formflow-overview'>
        {/* Display and modify the form title */}
        <label>
            <h4>Form title</h4>
            <input
                type='text'
                style={ { minWidth: '400px' } }
                value={ details.title }
                onChange={ e => updateTitle( e.target.value ) }
            />
        </label>
        <br /><br />

        {/* Display the unique shortcode and allow copying */}
        { details.id && details.id !== 'new' && // not shown for new forms
        <div>
            <h4>Shortcode</h4>
            <div className='shortcode-wrap'>
                <span className='shortcode'>
                    { shortcode }
                </span>
                <Button
                    type='secondary'
                    onClick={ copyShortcode }
                >
                    Copy
                </Button>
                <span className={ classNames( 'copy-message', { 'is-active': copyMessage } ) }>
                    Copied to clipboard
                </span>
            </div>
            <br /><br />
        </div> }

        {/* Gives the option to delete the form, will warnings */}
        { details.id && details.id !== 'new' && // not shown for new forms
        <div>
            <br /><br /><hr />
            <h4>Delete form</h4>

            <div className='notice-warning'>
                <p>This will delete all submissions, fields, and settings for this form. You will not be able to recover this data.</p>
            </div>

            <p>If you are sure you want to delete this form, type <span className='delete-span'>DELETE</span> into the text field to unlock the delete button.</p>

            <div className='delete-wrap'>
                <input
                    type="text"
                    style={ { marginBottom: '7px' } }
                    onChange={ e => checkUnlockDelete( e.target.value ) }
                />
                <Button
                    type='secondary'
                    disabled={ ! unlockDelete }
                    onClick={ deleteForm }
                >
                    Delete form and data
                </Button>
                <br /><br />
            </div>
        </div> }

    </div>
}
