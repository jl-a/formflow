import React from 'react'
import { RootState } from '../../../utils/store/store'
import { useDispatch, useSelector } from 'react-redux'
import { updateDetail } from '../../../utils/store/details'

export default () => {
    const details = useSelector( ( state: RootState ) => state.details.value )
    const dispatch = useDispatch()
    const pageTitle = document.getElementById( 'formflow-title' )

    const updateTitle = ( title: any ) => {
        dispatch( updateDetail( { title } ) )
        if ( pageTitle && details.id !== 'new' ) {
            pageTitle.innerHTML = `Edit ${ title } - Form Flow`
        }
    }

    return <div>
        <label>
            <p>Form title</p>
            <input
                type='text'
                style={ { minWidth: '400px' } }
                value={ details.title }
                onChange={ e => updateTitle( e.target.value ) }
            />
        </label>
    </div>
}
