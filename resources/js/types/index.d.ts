export interface Auth {
    id: number;
    name: string;
    nickname: string;
    email: string;
    group_level: number;
    email_verified_at?: string;
    status: number;
    profile_photo_path?: string;
    created_at: string;
    point: number;
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: Auth;
    };
    flash: {
        status: string;
        message: string;
        error: string;
    };
};
