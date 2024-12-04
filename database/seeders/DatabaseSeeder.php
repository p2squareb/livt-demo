<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\BoardWrite;
use App\Models\Category;
use App\Models\System;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $accessibleMenus = 'B1,B2,C1,C2,D1,D2,D3,D4,E1,E2';        
        UserGroup::insert([
            ['name' => '최고관리자', 'level' => 99, 'comment' => '최고관리자', 'accessible_menus' => $accessibleMenus, 'created_at' => now(), 'updated_at' => now()],
            ['name' => '관리자', 'level' => 11, 'comment' => '관리자', 'accessible_menus' => '', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '일반회원', 'level' => 1, 'comment' => '일반회원', 'accessible_menus' => '', 'created_at' => now(), 'updated_at' => now()],
        ]);

        User::insert([
            'name' => '운영자',
            'nickname' => '운영자',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('1234'),
            'group_level' => 99,
            'provider' => 'email',
            'last_login_at' => now(),
            'login_ip' => fake()->ipv4(),
        ]);

        User::factory(30)->create();

        $configData = [
            'basic' => [
                'site_name' => 'LIVT-DEMO',
                'site_description' => '리프트, livt, 무료 홈페이지, boilerplate, admin dashboard, admin panel, 관리자 시스템, 게시판, 댓글, 회원가입, 로그인, 로그아웃',
                'domain_name' => 'demo.livt.dev',
                'sq_email' => 'admin@gmail.com',
                'use_smtp' => '1',
                'use_external_email' => '0',
                'use_dormant' => '0',
            ],
            'image' => [
                'image_resize' => '1',
                'image_width_max' => '1280',
            ],
        ];
        System::insert([
            'register_ip' => request()->ip(),
            'register_id' => 'system',
            'title' => 'basic',
            'content' => json_encode($configData),
        ]);

        $configData = [
            'point' => [
                'use_point_join' => '1',
                'use_point_join_amt' => '1000',
                'use_point_login' => '1',
                'use_point_login_amt' => '10',
                'use_point_write' => '1',
                'use_point_write_amt' => '10',
                'use_point_write_comment' => '1',
                'use_point_write_comment_amt' => '5',
                'use_point_write_up' => '1',
                'use_point_write_up_amt' => '10',
                'use_point_comment' => '1',
                'use_point_comment_amt' => '5',
                'use_point_comment_up' => '1',
                'use_point_comment_up_amt' => '5',
            ],
        ];
        System::insert([
            'register_ip' => request()->ip(),
            'register_id' => 'system',
            'title' => 'point',
            'content' => json_encode($configData),
        ]);

        Board::insert([
            'table_id' => 'default',
            'table_name' => '자유게시판',
            'status' => 1,
            'use_category' => 1,
            'category_list' => '구글,애플,네이버,카카오,페이스북,인스타그램,트위터,유튜브',
            'write_level' => 1,
            'use_comment' => 1,
            'use_rate' => 1,
            'use_report' => 1,
            'use_tags' => 1,
            'skin' => 'card',
        ]);

        BoardWrite::factory(100)->create();

        Category::insert([
            ['target_name' => 'inquiry', 'category' => '오류/버그'],
            ['target_name' => 'inquiry', 'category' => '건의/제안'], 
            ['target_name' => 'inquiry', 'category' => '회원/정보관리'],
            ['target_name' => 'inquiry', 'category' => '기타']
        ]);
    }
}
