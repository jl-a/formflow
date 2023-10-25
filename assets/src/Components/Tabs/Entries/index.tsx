import React from 'react'
import { useSelector } from 'react-redux'
import { RootState } from '../../../utils/store/store'
import './style.scss'

export default () => {
    const fields = useSelector( ( state: RootState ) => state.fields.value )

    const columns = []
    const rootFields = fields.filter( field => field.parent === 'root' )

    for ( let index = 0; index < Math.min( 4, rootFields.length ); index++ ) {
        columns.push( {
            id: rootFields[ index ].id,
            title: rootFields[ index ].title,
        } )
    }

    return <div className='formflow-entries'>
        <table>
            <thead>
                <tr>
                    { columns.map( column => <td>{ column.title }</td> ) }
                    <td>Date Submitted</td>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>

        <p style={ { textAlign: 'center' } }>
            Form submissions will be shown here in the future
        </p>
    </div>
}
