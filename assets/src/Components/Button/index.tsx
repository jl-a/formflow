import React from 'react'
import { FieldElementProps } from '../../utils/types'
import './style.scss'

interface AddNewElementProps extends FieldElementProps {
    onClick: () => void
}

/**
 * Renders a button that handles clicks
 * @example
 * import Button from '../AddNew'
 * <AddNew parent={ field_id } />
 */
const Element = ( props: AddNewElementProps ) => {

    return <div className='formflow-item'>
        <div
            className="button button-primary"
            onClick={ props.onClick }
        >
            { props.children }
        </div>
    </div>
}

export default Element
