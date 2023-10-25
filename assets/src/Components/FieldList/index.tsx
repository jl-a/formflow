import React from 'react'
// Stores and props
import { useSelector, useDispatch } from 'react-redux'
import { RootState } from '../../utils/store/store'
import { getFields } from '../../utils/store/fields'
import { FieldElementProps } from '../../utils/types'
import { addField } from '../../utils/store/fields'
// Components
import Button from '../Button'
import Field from '../Field'

interface FieldListElementProps extends FieldElementProps {
    parent: string,
}

const Element = ( props: FieldListElementProps ) => {

    const fields = useSelector( ( state: RootState ) => getFields( state, props.parent ) )
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
            <Field
                field={ field }
                key={ field.id }
            />
        )
    }

    /**
     * Renders the full field list
     */
    return <div className='formflow-field-list'>
        { rows }
        <Button onClick={ addNew } type='secondary'>
            Add New Field
        </Button>
    </div>
}

export default Element
