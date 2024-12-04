export interface MenuItem {
    id: string;
    icon: string;
    title: string;
    link: string;
    active: boolean;
    activeDropdown: boolean;
    subMenu?: SubMenuItem[];
}

export interface SubMenuItem {
    id: string;
    icon: string;
    title: string;
    link: string;
    active: boolean;
}
