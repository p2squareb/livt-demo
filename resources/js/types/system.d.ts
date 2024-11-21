interface SystemBasic {
    basic: {
        domain_name: string;
        site_name: string;
        site_description: string;
        sq_email: string;
        use_dormant: string;
        use_external_email: string;
        use_smtp: string;
    },
    image: {
        image_resize: string;
        image_width_max: string;
    }
}

interface SystemExternal {
    use_google_analytics: boolean;
    google_analytics_id: string;
    use_google_login: boolean,
    google_client_id: string,
    google_client_secret: string,
    use_naver_login: boolean,
    naver_client_id: string,
    naver_client_secret: string,
    use_kakao_login: boolean,
    kakao_client_id: string,
    kakao_client_secret: string,
    use_kakao_map: boolean,
    kakao_javascript_key: string,
}

interface SystemPolicy {
    policy: {
        terms: string;
        policy: string;
    }
}

interface SystemPoint {
    point: {
        use_point_join: string;
        use_point_join_amt: string;
        use_point_login: string;
        use_point_login_amt: string;
        use_point_write: string;
        use_point_write_amt: string;
        use_point_write_comment: string;
        use_point_write_comment_amt: string;
        use_point_write_up: string;
        use_point_write_up_amt: string;
        use_point_comment: string;
        use_point_comment_amt: string;
        use_point_comment_up: string;
        use_point_comment_up_amt: string;
    }
}

