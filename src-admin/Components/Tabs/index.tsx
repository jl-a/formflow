import React from 'react'
import { useDispatch, useSelector } from 'react-redux'
import { RootState } from '../../utils/store/store'
import { updateApp } from '../../utils/store/app'
import Fields from './Fields'
import Button from '../Button'
import save from '../../utils/save'
import Overview from './Overview'
import Actions from './Actions'
import Entries from './Entries'
import './style.scss'

export default () => {
    const app = useSelector( ( state: RootState ) => state.app.value )
    const details = useSelector( ( state: RootState ) => state.details.value )
    const fields = useSelector( ( state: RootState ) => state.fields.value )

    const dispatch = useDispatch()

    const onSaveClick = async () => {
        dispatch( updateApp( { saving: true } ) )
        await save( details, fields )
        dispatch( updateApp( { saving: false } ) )
    }

    return <div className='formflow-tabview'>
        <div id='formflow-tabwrap'>
            { app.tab === 'overview' && <Overview /> }
            { app.tab === 'entries' && <Entries /> }
            { app.tab === 'fields' && <Fields /> }
            { app.tab === 'actions' && <Actions /> }
        </div>
        <div id='formflow-footer'>
            <Button
                className="formflow-save-button"
                disabled={ !! app.saving }
                onClick={ onSaveClick }
            >
                Save Form
            </Button>
        </div>
    </div>
}
