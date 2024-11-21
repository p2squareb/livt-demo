export interface MenuItem {
    icon: string;
    pageName: string;
    title: string;
    link: string;
    active: boolean;
    activeDropdown: boolean;
    subMenu?: SubMenuItem[];
}

export interface SubMenuItem {
    icon: string;
    pageName: string;
    title: string;
    link: string;
    active: boolean;
}
