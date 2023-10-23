import React from 'react'
import { useSelector } from 'react-redux'
import { RootState } from '../../utils/store/store'
import Fields from './Fields'
import './style.scss'
import Overview from './Overview'

export default () => {
    const app = useSelector( ( state: RootState ) => state.app.value )

    return <div className='formflow-tabview'>
        { app.tab === 'overview' && <Overview /> }
        { app.tab === 'fields' && <Fields /> }
    </div>
}
