import React from 'react'
import { RootState } from '../../utils/store/store'
import { useDispatch, useSelector } from 'react-redux'
import { updateApp } from '../../utils/store/edit--app'
import type { EditApp } from '../../utils/types'
import classNames from 'classnames'
import './style.scss'

export default () => {
    const tabs: Array<{ id: EditApp['tab'], title: string }> = [ {
        id: 'overview',
        title: 'Overview',
    }, {
        id: 'entries',
        title: 'Entries',
    }, {
        id: 'fields',
        title: 'Fields',
    }, {
        id: 'actions',
        title: 'Actions',
    }, {
        id: 'settings',
        title: 'Settings',
    } ]

    const app = useSelector( ( state: RootState ) => state.app.value )
    const dispatch = useDispatch()

    const onClick = ( tabId: EditApp['tab'] ) => {
        dispatch( updateApp( { tab: tabId } ) )
    }

    return <ul className="formflow-tablist">
        { tabs.map( ( tab, index ) => (
            <li
                key={ index }
                className={ classNames( 'tab', `tab-${ tab.id }`, { 'active': app.tab === tab.id } ) }
                onClick={ () => onClick( tab.id ) }
            >
                { tab.title }
            </li>
        ) ) }
    </ul>
}
