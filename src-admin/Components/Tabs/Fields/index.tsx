import React from 'react'
import FieldList from '../../FieldList'
import './style.scss'

export default () => {
    return <div className='formflow-fields'>
        <div className='fieldlist'>
            <FieldList parent='root' />
        </div>
        <div className='fieldsettings'>

        </div>
    </div>
}
