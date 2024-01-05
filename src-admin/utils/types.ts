/** Store for the Edit app */
type EditApp = {
    saving: boolean
    /** ID of the current editor tab in active view */
    tab: 'overview' | 'entries' | 'fields' | 'actions' | 'settings'
}

/**
 * The type for global settings. Contains two keys: one for fixed settings and one for
 * settings that integrations register. See the IntegrationSetting type for details on
 * what each integration's settings may contain.
 *
 * @example
 * integrations: {
 *   google_recaptcha: {
 *     api_key: {...},
 *     widget: {...}
 *   }
 * }
 */
type Settings = {
    settings: {},
    integrations: {
        [integration_id: string]: {
            [setting_id: string]: IntegrationSetting
        }
    }
}

type IntegrationSetting = {
    id: string
    title: string
    value: any
    type: string
    options?: { [id: string]: string }
    conditional?: Array<Array<string> | string>
};

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
    EditApp,
    Settings,
    IntegrationSetting,
    FormData,
    FieldData,
    DetailsData,
    FieldElementProps,
    RootElementProps,
}
