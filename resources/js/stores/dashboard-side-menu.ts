import { defineStore } from "pinia";
import { MenuItem } from "@/types/side-menu";

export const useDashboardSideMenuStore = defineStore({
    id: "dashboard-side-menu",
    state: () => ({
        menu: [
            {
                id: 'A',
                icon: "dashboard",
                title: "대시보드",
                link: "/admin",
                active: false,
                activeDropdown: false,
            },
            {
                id: 'B',
                icon: "cog",
                title: "시스템 설정",
                active: false,
                activeDropdown: false,
                subMenu: [
                    {
                        id: 'B1',
                        icon: "",
                        title: "기본 환경설정",
                        link: "/admin/system/basic",
                        active: false,
                    },
                    {
                        id: 'B2',
                        icon: "",
                        title: "서비스 약관",
                        link: "/admin/system/policy-terms",
                        active: false,
                    },
                    {
                        id: 'B3',
                        icon: "",
                        title: "외부서비스 설정",
                        link: "/admin/system/external",
                        active: false,
                    },
                ],
            },
            {
                id: 'C',
                icon: "users",
                title: "회원 관리",
                link: "",
                active: false,
                activeDropdown: false,
                subMenu: [
                    {
                        id: 'C1',
                        icon: "",
                        title: "회원 리스트",
                        link: "/admin/user/list",
                        active: false,
                    },
                    {
                        id: 'C2',
                        icon: "",
                        title: "회원 그룹",
                        link: "/admin/user/group-list",
                        active: false,
                    },
                ],
            },
            {
                id: 'D',
                icon: "table-row",
                title: "게시판 관리",
                link: "",
                active: false,
                activeDropdown: false,
                subMenu: [
                    {
                        id: 'D1',
                        icon: "",
                        title: "게시판 리스트",
                        link: "/admin/board/list",
                        active: false,
                    },
                    {
                        id: 'D2',
                        icon: "",
                        title: "게시글 관리",
                        link: "/admin/board/write-list",
                        active: false,
                    },
                    {
                        id: 'D3',
                        icon: "",
                        title: "댓글 관리",
                        link: "/admin/board/comment-list",
                        active: false,
                    },
                    {
                        id: 'D4',
                        icon: "",
                        title: "1:1 문의",
                        link: "/admin/board/inquiry-list",
                        active: false,
                    },
                ],
            },
            {
                id: 'E',
                icon: "point",
                title: "포인트 관리",
                link: "",
                active: false,
                activeDropdown: false,
                subMenu: [
                    {
                        id: 'E1',
                        icon: "",
                        title: "포인트 설정",
                        link: "/admin/point/setting",
                        active: false,
                    },
                    {
                        id: 'E2',
                        icon: "",
                        title: "포인트 내역",
                        link: "/admin/point/list",
                        active: false,
                    },
                ],
            },
        ] as MenuItem[],
        isSidebarOpen: true
    }),
    actions: {
        toggleSidebar() {
            this.isSidebarOpen = !this.isSidebarOpen;
        }
    }
});
