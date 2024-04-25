import { createSlice } from '@reduxjs/toolkit'
import { createSelector } from '@reduxjs/toolkit'
import type { PayloadAction } from '@reduxjs/toolkit'
import { FieldData } from '../types'
import { RootState } from './store'
import { normaliseFieldData, normalisePositions, initialForm } from '../normalise'

interface FieldsState {
    value: Array<FieldData>
}

const initialState: FieldsState = {
    value: initialForm.fields,
}

/**
 * The slice for all fields. Contains reducers with mutating logic that allow changes
 * to be made on the store.
 * @example
 * import { useDispatch } from 'react-redux'
 * import { initialiseFields } from '../fields.ts'
 * ...
 * const dispatch = useDispatch()
 * dispatch( initialiseFields( formData.fields ) )
 */
export const fieldsSlice = createSlice( {
    name: 'fields',
    initialState,
    reducers: {
        /**
         * Initialises the state, and overwrites it with a new array of Field Data.
         * @param state
         * @param data      The array of Field Data to overwrite the state with
         */
        setFields: ( state, data: PayloadAction<Array<FieldData>> ) => {
            state.value = data.payload
        },
        /**
         * Creates a new field and appends it to the list.
         * @param state
         * @param parentId  The new field's parent ID
         */
        addField: ( state, parentId: PayloadAction<string> ) => {
            let highestPosition = -1
            for ( const field of state.value ) {
                if ( field.parent === parentId.payload && field.position > highestPosition ) {
                    highestPosition = field.position
                }
            }
            state.value.push( normaliseFieldData( {
                parent: parentId.payload,
                position: highestPosition + 1
            } ) )
        },
        /**
         * Updates all the components of a field to a new Field Data item
         * @param state
         * @param field
         */
        updateField: ( state, field: PayloadAction<FieldData> ) => {
            const updatedField = normaliseFieldData( field.payload ) // normalise the data so we can be certain everything essential is there
            const index = state.value.findIndex( stateField => stateField.id === updatedField.id )
            if ( index > -1 ) { // no upserting, only update if the ID exists
                state.value[ index ] = updatedField
            }
        },
        deleteField: ( state, fieldId: PayloadAction<string> ) => {
            const index = state.value.findIndex( stateField => stateField.id === fieldId.payload )
            if ( index > -1 ) {
                const parentId = state.value[ index ].parent
                state.value.splice( index, 1 )
                normalisePositions( state.value, parentId )
            }
        },
        updatePositions: ( state, parentId: PayloadAction<string> ) => {
            normalisePositions( state.value, parentId.payload )
        }
    },
} )

/**
 * A selector that returns all fields that have the supplied parent ID, and sorted
 * in order.
 * @param state     The store's root state
 * @param parent    The parent ID that the fields belong to. Defaults to 'root'
 * @example
 * import { useSelector } from 'react-redux'
 * import { RootState } from '../../utils/store'
 * import { getAllFields } from '../../utils/fields'
 * ...
 * const fields = useSelector( ( state: RootState ) => getAllFields( state, parent_id ) )
 */
export const getFields = createSelector(
    [
        ( state: RootState ) => state.fields.value,
        ( _state: RootState, parent: string ) => parent
    ],
    ( fields: Array<FieldData>, parent: string ) => {
        if ( ! parent ) {
            parent = 'root'
        }
        const filteredFields = fields
            .filter( field => { // Filters by the parent ID
                return field.parent === parent
            } )
            .sort( ( a, b ) => { // Sorts by the position property
                if ( a.position > b.position ) {
                    return 1
                }
                if ( a.position < b.position ) {
                    return -1
                }
                return 0
            } )

        return filteredFields
    }
)

export const getField = createSelector(
    [
        ( state: RootState ) => state.fields.value,
        ( _state: RootState, id: string ) => id
    ],
    ( fields: Array<FieldData>, id: string ) => {
        return fields.find( field => field.id === id )
    }
)

export const {
    setFields,
    addField,
    updateField,
    deleteField,
} = fieldsSlice.actions

export default fieldsSlice.reducer
