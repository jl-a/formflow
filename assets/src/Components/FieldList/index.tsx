import React from 'react'
import { RootState } from '../../utils/store'
import { useSelector, useDispatch } from 'react-redux'
import { getAllFields } from '../../utils/fields'
import { FieldElementProps } from '../../utils/types'
import { addField } from '../../utils/fields'
import Button from '../Button'

interface FieldListElementProps extends FieldElementProps {
    parent: string,
}

const Element = ( props: FieldListElementProps ) => {

    const fields = useSelector( ( state: RootState ) => getAllFields( state, props.parent ) )
    const dispatch = useDispatch()

    /**
     * Dispatches a call to create a new field, and append it to the current field list
     */
    const addNew = () => {
        dispatch( addField( props.parent ) )
    }

    /**
     * Renders the list of fields
     */
    const rows = []
    for ( const field of fields ) {
        rows.push(
            <div
                id={ field.id }
                key={ field.id }
            >
                <input type={ field.type } />
            </div>
        )
    }

    /**
     * Renders the full field list
     */
    return <div className='formflow-field-list'>
        { rows }
        <Button onClick={ addNew }>
            Add New Field
        </Button>
    </div>
}

export default Element
