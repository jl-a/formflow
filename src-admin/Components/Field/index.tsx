import React from 'react'
import { useDispatch } from 'react-redux'
import { FieldElementProps as BaseFieldElementProps, FieldData } from '../../utils/types'
import { updateField, deleteField } from '../../utils/store/fields'
import './style.scss'

interface FieldElementProps extends BaseFieldElementProps {
    field: FieldData
}

const Element = ( props: FieldElementProps ) => {
    const dispatch = useDispatch()

    const onChange = ( key: string, value: any ) => {
        dispatch( updateField( {
            ...props.field,
            [ key ]: value
        } ) )
    }

    const remove = () => {
        dispatch( deleteField( props.field.id ) )
    }

    return (
        <div
            id={ props.field.id }
            className='formflow-field'
        >
            <input
                type='text'
                value={ props.field.title }
                onChange={ e => onChange( 'title', e.target.value ) }
            />
            <select
                value={ props.field.type || 'text' }
                onChange={ e => onChange( 'type', e.target.value ) }
            >
                <option value='text'>Text</option>
                <option value='email'>Email</option>
                <option value='paragraph'>Paragraph</option>
            </select>
            <span
                className='icon-remove'
                onClick={ remove }
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M12 4h3c.6 0 1 .4 1 1v1H3V5c0-.6.5-1 1-1h3c.2-1.1 1.3-2 2.5-2s2.3.9 2.5 2zM8 4h3c-.2-.6-.9-1-1.5-1S8.2 3.4 8 4zM4 7h11l-.9 10.1c0 .5-.5.9-1 .9H5.9c-.5 0-.9-.4-1-.9L4 7z"/></g></svg>
            </span>
        </div>
    )
}

export default Element
