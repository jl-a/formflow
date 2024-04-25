import { createSlice } from '@reduxjs/toolkit'
import type { PayloadAction } from '@reduxjs/toolkit'
import { EditApp } from '../types'

interface AppState {
    value: EditApp
}

const initialState: AppState = {
    value: {
        saving: false,
        tab: 'overview'
    }
}

export const appSlice = createSlice( {
    name: 'app',
    initialState,
    reducers: {
        /**
         * Updates item to new data
         * @param state
         * @param field
         */
        updateApp: ( state, data: PayloadAction<Partial<AppState>> ) => {
            state.value = { ...state.value, ...data.payload }
        }
    }
} )

export const {
    updateApp
} = appSlice.actions

export default appSlice.reducer
