/** Store for the Edit app */
type App = {
    /** Whether the current form state is actively being synced to the backend database. */
    saving: boolean
    /** ID of the current editor tab in active view */
    tab: 'overview' | 'entries' | 'fields' | 'actions' | 'settings'
}

type FormData = {
    details: {
        id: string
        title: string
    }
    settings: {}
    fields: Array<FieldData>
}

type FieldData = {
    id: string
    parent: string
    title: string
    type: string
    position: number
}

type DetailsData = {
    id: string
    title: string
}

interface FieldElementProps extends React.HTMLAttributes<HTMLDivElement> {}

interface RootElementProps {
    el: HTMLElement,
}

export {
    App,
    FormData,
    FieldData,
    DetailsData,
    FieldElementProps,
    RootElementProps,
}
