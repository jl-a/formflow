import { createSlice } from '@reduxjs/toolkit'
import { createSelector } from '@reduxjs/toolkit'
import type { PayloadAction } from '@reduxjs/toolkit'
import { FieldData } from './types'
import { RootState } from './store'
import { normaliseFieldData } from './normalise'

interface FieldsState {
    value: Array<FieldData>
}

const initialState: FieldsState = {
    value: [],
}

/**
 * The slice for all fields. Contains reducers with mutating logic that allow changes
 * to be made on the store.
 */
export const fieldsSlice = createSlice( {
    name: 'fields',
    initialState,
    reducers: {
        /**
         * Initialises the state, and overwrites it with a new array of Field Data.
         * @param state
         * @param action
         * @example
         * import { useDispatch } from 'react-redux'
         * import { initialise } from '../fields.ts'
         * ...
         * const dispatch = useDispatch()
         * dispatch( initialise( formData.fields ) )
         */
        initialise: ( state, action: PayloadAction<Array<FieldData>> ) => {
            state.value = action.payload
        },
        /**
         * Creates a new field and appends it to the list.
         * @param state
         * @param action
         * @example
         * import { useDispatch } from 'react-redux'
         * import { addField } from '../fields.ts'
         * ...
         * const dispatch = useDispatch()
         * dispatch( addField( parent_id ) )
         */
        addField: ( state, action: PayloadAction<string> ) => {
            state.value.push( normaliseFieldData( {
                parent: action.payload
            } ) )
        }
    },
} )

const getFields = ( state: RootState ) => state.fields.value
const getFieldParent = ( _state: RootState, parent: string ) => parent
const getFieldId = ( _state: RootState, id: string ) => id

export const getAllFields = createSelector(
    [ getFields, getFieldParent ],
    ( fields: Array<FieldData>, parent: string ) => {
        if ( ! parent ) {
            parent = 'root'
        }
        const filteredFields = fields
            .filter( field => { // Filders by the parent ID
                return field.parent === parent
            } )
            .sort( ( a, b ) => { // Sorts by the position property
                if ( a.position > b.position ) {
                    return -1
                }
                if ( a.position < b.position ) {
                    return 1
                }
                return 0
            } )

        return filteredFields
    }
)

export const getSingleField = createSelector(
    [ getFields, getFieldId ],
    ( fields: Array<FieldData>, id: string ) => {
        return fields.find( field => field.id === id )
    }
)

export const {
    initialise,
    addField,
} = fieldsSlice.actions

export default fieldsSlice.reducer
