export interface UserGroup {
    id: number;
    name: string;
    level: number;
    comment: string;
    created_at: string;
    users_count: number;
}

export interface User {
    id: number;
    status: number;
    name: string;
    nickname: string;
    email: string;
    email_verified_at?: string;
    group_level: number;
    group: UserGroup;
    point: number;
    provider: string;
    provider_id: string;
    provider_token: string;
    provider_refresh_token: string;
    last_login_at?: string;
    login_ip?: string;
    leaved_at?: string;
    profile_photo_path?: string | null;
    created_at: string;
}

export interface PaginatedUserList {
    current_page: number;
    data: User[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: Array<{url: string, label: string, active: boolean}>;
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: string;
    to: number;
    total: number;
}

export interface UserProhibit {
    id: number;
    user_id: number;
    created_at: string;
    gubun: number;
    period_type: string;
    until_date: string;
    cause: string;
    processed_by_user_id: number;
    processed_by_user_nickname: string;
}

export interface UserDormant {
    id: number;
    user_id: number;
    created_at: string;
    gubun: number;
}

export interface LoginRecord {
    id: number;
    created_at: string;
    user_id: number;
    ip_address: string;
    user_agent: string;
    provider: string;
    login_at: string;
    user: User;
}

export interface Point {
    id: number;
    created_at: string;
    point_type: string;
    point_item: string;
    to_user_id: number;
    from_user_id: number;
    reason: string;
    amount: number;
    target_name: string;
    target_id: number;
    processed_by: string;
    user: User;
    sender: User;
}

export interface PaginatedPointList {
    current_page: number;
    data: Point[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: Array<{url: string, label: string, active: boolean}>;
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: string;
    to: number;
    total: number;
}
