import React from 'react'
import { useSelector } from 'react-redux'
import { RootState } from '../../../utils/store/store'
import Button from '../../Button'
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

    const reload = () => {}

    return <div className='formflow-entries'>
        <div className='entries-controls'>
            <Button
                onClick={ reload }
                type='secondary'
            >
                Reload entries
            </Button>
            <label>
                Entries per page
                <select>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select>
            </label>
        </div>
        <table>
            <thead>
                <tr>
                    { columns.map( ( column, index ) => (
                        <td key={ index }>
                            { column.title }
                        </td>
                    ) ) }
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
