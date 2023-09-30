import { createSlice } from '@reduxjs/toolkit'
import type { PayloadAction } from '@reduxjs/toolkit'
import { DetailsData } from '../types'
import { initialForm } from '../normalise'

interface DetailsState {
    value: DetailsData
}

const initialState: DetailsState = {
    value: initialForm.details,
}

/**
 * The slice for all form details.
 * @example
 * import { useDispatch } from 'react-redux'
 * import { initialiseDetails } from '../details.ts'
 * ...
 * const dispatch = useDispatch()
 * dispatch( initialiseDetails( formData.details ) )
 */
export const detailsSlice = createSlice( {
    name: 'details',
    initialState,
    reducers: {
        /**
         * Initialises the state, and overwrites it with a new array of Field Data.
         * @param state
         * @param data      The array of Field Data to overwrite the state with
         */
        initialiseDetails: ( state, data: PayloadAction<DetailsData> ) => {
            state.value = data.payload
        },
    }
} )

export const {
    initialiseDetails,
} = detailsSlice.actions

export default detailsSlice.reducer
