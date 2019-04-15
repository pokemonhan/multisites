<?php

use Illuminate\Database\Seeder;

class PartnerAdminMenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('partner_admin_menus')->delete();
        
        \DB::table('partner_admin_menus')->insert(array (
            0 => 
            array (
                'id' => 1,
                'label' => '活动',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'label' => '手机活动图片设置',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 1,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'label' => '活动列表',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 1,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'label' => '核心',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'label' => '玩法类型',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 4,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'label' => '基础投注方式',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 4,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'label' => '基础玩法',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 4,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'label' => '活动类型',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 4,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'label' => '账变类型',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 4,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'label' => '资金流',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 4,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 12,
                'label' => '系统设置',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 13,
                'label' => '系统设置',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 12,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 14,
                'label' => '内容管理',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 15,
                'label' => '分类管理',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 14,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 16,
                'label' => '文章管理',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 14,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 17,
                'label' => '管理员',
                'en_name' => 'manager',
                'route' => '#',
                'pid' => 0,
                'icon' => 'anticon anticon-user',
                'class' => 'null',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 21,
                'label' => '管理员角色',
                'en_name' => 'manager.character',
                'route' => '/manager/manager-character',
                'pid' => 17,
                'icon' => 'null',
                'class' => 'null',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 25,
                'label' => '消息',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 26,
                'label' => '消息类型',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 25,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 27,
                'label' => '消息',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 25,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 28,
                'label' => '发布消息',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 25,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 29,
                'label' => '基础信息',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 30,
                'label' => '充值白名单',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 29,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 31,
                'label' => '系列投注方式',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 29,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 32,
                'label' => '密码卡',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 29,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 33,
                'label' => '开奖中心',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 29,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 34,
                'label' => '银行管理',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 29,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 35,
                'label' => '地区',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 29,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 36,
                'label' => '分红规则设置',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 29,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 37,
                'label' => '域名管理',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 29,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 38,
                'label' => '广告',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 39,
                'label' => '广告类型',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 38,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 40,
                'label' => '广告位',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 38,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 41,
                'label' => '广告内容',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 38,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 46,
                'label' => '游戏',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 47,
                'label' => '游戏',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 46,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 48,
                'label' => '系列玩法',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 46,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 49,
                'label' => '玩法组',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 46,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 50,
                'label' => '投注方式与玩法关系',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 46,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 51,
                'label' => '奖期',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 46,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 52,
                'label' => '系列',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 46,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 53,
                'label' => '开奖',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 54,
                'label' => '开奖',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 53,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 55,
                'label' => '抓取记录',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 53,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 56,
                'label' => '推送记录',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 53,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 57,
                'label' => '告警记录',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 53,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 58,
                'label' => '奖金组',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 59,
                'label' => '奖金组',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 58,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 60,
                'label' => '用户奖金组',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 58,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 61,
                'label' => '用户奖金组浮动',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 58,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 62,
                'label' => '投注',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 63,
                'label' => '注单',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 62,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 64,
                'label' => '追号',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 62,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 65,
                'label' => '资金',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 66,
                'label' => '账户',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 65,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 67,
                'label' => '账变',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 65,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 68,
                'label' => '分红',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 65,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 69,
                'label' => '提现记录（审核通过）',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 65,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 70,
                'label' => '提现记录（除审核通过的）',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 65,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 71,
                'label' => '用户管理',
                'en_name' => 'user.manage',
                'route' => '/user',
                'pid' => 0,
                'icon' => 'anticon anticon-appstore',
                'class' => 'null',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 72,
                'label' => '用户绑定动作',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 71,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 76,
                'label' => '用户管理',
                'en_name' => 'manage.user',
                'route' => '/user/manage-user',
                'pid' => 71,
                'icon' => 'null',
                'class' => 'null',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 77,
                'label' => '创建总代',
                'en_name' => 'create.general.agent',
                'route' => '/user/create-general-agent',
                'pid' => 71,
                'icon' => 'anticon anticon-appstore',
                'class' => 'null',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 78,
                'label' => '密码审核',
                'en_name' => 'passport.check',
                'route' => '/user/passport-check',
                'pid' => 71,
                'icon' => 'null',
                'class' => 'null',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 79,
                'label' => '资金密码审核',
                'en_name' => 'capital.passport.check',
                'route' => '/user/capital-passport-check',
                'pid' => 71,
                'icon' => 'null',
                'class' => 'null',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 80,
                'label' => '代理分布',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 71,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 81,
                'label' => '开户链接管理',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 71,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 82,
                'label' => '代理奖金组管理',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 71,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 83,
                'label' => '卡号反查',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 71,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 84,
                'label' => '代理盈亏报表',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 71,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 85,
                'label' => '绑定银行卡',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 71,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 86,
                'label' => '用户第三方游戏',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 71,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 87,
                'label' => '转移下级用户列表',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 71,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 88,
                'label' => '预约总代',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 71,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 89,
                'label' => '报表',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 90,
                'label' => '充值记录',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 91,
                'label' => '分红报表',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 92,
                'label' => '日工资报表',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 93,
                'label' => '盈亏报表查询',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 94,
                'label' => '用户佣金',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 95,
                'label' => '手动充值记录',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 96,
                'label' => '用户彩种盈亏记录',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 97,
                'label' => '提现记录',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 98,
                'label' => '异常充值记录',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 99,
                'label' => '盈亏记录',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 100,
                'label' => '单期盈亏记录',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 89,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 101,
                'label' => '日志',
                'en_name' => 'log',
                'route' => '/log',
                'pid' => 0,
                'icon' => 'anticon anticon-profile',
                'class' => 'null',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 102,
                'label' => '操作日志',
                'en_name' => 'operation.log',
                'route' => '/log/operation-log',
                'pid' => 101,
                'icon' => 'null',
                'class' => 'null',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 108,
                'label' => '菜单管理',
                'en_name' => NULL,
                'route' => '#',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 109,
                'label' => '菜单编辑',
                'en_name' => NULL,
                'route' => '/menu',
                'pid' => 0,
                'icon' => NULL,
                'class' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}