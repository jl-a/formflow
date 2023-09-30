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
    FormData,
    FieldData,
    DetailsData,
    FieldElementProps,
    RootElementProps,
}
