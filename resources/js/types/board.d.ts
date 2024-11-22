import { User } from "@/types/user";

export interface Category {
    id: number;
    category: string;
    target_name: string;
}

export interface Board {
    id: number;
    table_id: string;
    table_name: string;
    status: boolean;
    use_category: boolean;
    category_list: string;
    write_level: number
    use_comment: boolean;
    use_rate: boolean;
    use_report: boolean;
    use_tags: boolean;
    skin: string;
    article_count: number;
    comment_count: number;
}

export interface Write {
    id: number;
    created_at: string;
    updated_at: string;
    board_id: number;
    table_id: string;
    categories: string;
    is_notice: boolean;
    subject: string;
    content: string;
    tags: string | null;
    hit: number;
    rate_up: number;
    rate_down: number;
    comment_count: number;
    report_count: number;
    user_id: number;
    writer: string;
    has_image: boolean;
    has_video: boolean;
    is_delete: boolean;
    deleted_at: string;
    list_file: string;
    user: User;
    board: Board;
    user_rate_up: boolean;
    user_rate_down: boolean;
    user_bookmark: boolean;
}

export interface PaginatedWriteList {
    current_page: number;
    data: Write[];
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

export interface Comment {
    id: number;
    board_id: number;
    table_id: string;
    write_id: number;
    parent_id: number;
    depth: number;
    rate_up: number;
    rate_down: number;
    user_id: number;
    writer: string;
    comment: string;
    is_delete: boolean;
    deleted_at: string;
    is_reported: boolean;
    ip: string;
    created_at: string;
    user_rate_up: boolean;
    user_rate_down: boolean;
    report_count: number;
    table_name: string;
    user: User;
    write: Write;
}

export interface PaginatedCommentList {
    current_page: number;
    data: Comment[];
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

export interface Report {
    id: number;
    created_at: string;
    target_table: string;
    target_id: number;
    user_id: number;
    target_user_id: number;
    user: User;
    target_user: User;
}

export interface Bookmark {
    id: number;
    created_at: string;
    target_table: string;
    target_id: number;
    user_id: number;
    ip: string;
    write: Write;
}

export interface Inquiry {
    id: number;
    created_at: string;
    category: string;
    is_delete: boolean;
    deleted_at: string;
    subject: string;
    content: string;
    user_id: number;
    answer_content: string;
    answer_user_id: number;
    answered_at: string;
    status: boolean;
    ip: string;
    user: User;
    answer: User;
}

export interface PaginatedInquiryList {
    current_page: number;
    data: Inquiry[];
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