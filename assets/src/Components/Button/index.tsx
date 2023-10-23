import React from 'react'
import classNames from 'classnames'
import { FieldElementProps } from '../../utils/types'
import './style.scss'

interface ButtonProps extends FieldElementProps {
    onClick: () => void,
    disabled?: boolean,
}

/**
 * Renders a button that handles clicks
 * @example
 * import Button from '../AddNew'
 * <AddNew parent={ field_id } />
 */
const Element = ( props: ButtonProps ) => {

    return <div className={ classNames( [ 'formflow-item', props.className ] ) }>
        <div
            className={ classNames( 'button button-primary', { 'disabled': props.disabled } ) }
            onClick={ props.onClick }
        >
            { props.children }
        </div>
    </div>
}

export default Element
