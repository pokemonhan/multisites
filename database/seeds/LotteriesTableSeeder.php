<?php

use Illuminate\Database\Seeder;

class LotteriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('lotteries')->delete();
        
        \DB::table('lotteries')->insert(array (
            0 => 
            array (
                'id' => 1,
                'cn_name' => '重庆时时彩',
                'en_name' => 'cqssc',
                'series_id' => 'ssc',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 59,
                'day_issue' => 59,
                'issue_format' => 'ymd|N3',
                'issue_type' => 'day',
                'valid_code' => '0,1,2,3,4,5,6,7,8,9',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'cn_name' => '新疆时时彩',
                'en_name' => 'xjssc',
                'series_id' => 'ssc',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 48,
                'issue_format' => 'ymd|N2',
                'issue_type' => 'day',
                'valid_code' => '0,1,2,3,4,5,6,7,8,9',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'cn_name' => '黑龙江时时彩',
                'en_name' => 'hljssc',
                'series_id' => 'ssc',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 42,
                'issue_format' => 'C7',
                'issue_type' => 'increase',
                'valid_code' => '0,1,2,3,4,5,6,7,8,9',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'cn_name' => '中兴1分彩',
                'en_name' => 'zx1fc',
                'series_id' => 'ssc',
                'is_fast' => 1,
                'auto_open' => 1,
                'max_trace_number' => 60,
                'day_issue' => 1440,
                'issue_format' => 'ymd|N4',
                'issue_type' => 'day',
                'valid_code' => '0,1,2,3,4,5,6,7,8,9',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'cn_name' => '腾讯分分彩',
                'en_name' => 'txffc',
                'series_id' => 'ssc',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 1440,
                'issue_format' => 'Ymd|N4',
                'issue_type' => 'day',
                'valid_code' => '0,1,2,3,4,5,6,7,8,9',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'cn_name' => '山东11选5',
                'en_name' => 'sd115',
                'series_id' => 'lotto',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 43,
                'issue_format' => 'ymd|N2',
                'issue_type' => 'day',
                'valid_code' => '01,02,03,04,05,06,07,08,09,10,11',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'cn_name' => '广东11选5',
                'en_name' => 'gd115',
                'series_id' => 'lotto',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 42,
                'issue_format' => 'ymd|N2',
                'issue_type' => 'day',
                'valid_code' => '01,02,03,04,05,06,07,08,09,10,11',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'cn_name' => '山西11选5',
                'en_name' => 'sx115',
                'series_id' => 'lotto',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 47,
                'issue_format' => 'ymd|N2',
                'issue_type' => 'day',
                'valid_code' => '01,02,03,04,05,06,07,08,09,10,11',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'cn_name' => '上海11选5',
                'en_name' => 'sh115',
                'series_id' => 'lotto',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 45,
                'issue_format' => 'ymd|N3',
                'issue_type' => 'day',
                'valid_code' => '01,02,03,04,05,06,07,08,09,10,11',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'cn_name' => '江西11选5',
                'en_name' => 'jx115',
                'series_id' => 'lotto',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 50,
                'day_issue' => 42,
                'issue_format' => 'Ymd-|N2',
                'issue_type' => 'day',
                'valid_code' => '01,02,03,04,05,06,07,08,09,10,11',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'cn_name' => '中兴11选5',
                'en_name' => 'zx115',
                'series_id' => 'lotto',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 50,
                'day_issue' => 1440,
                'issue_format' => 'Ymd|N4',
                'issue_type' => 'day',
                'valid_code' => '01,02,03,04,05,06,07,08,09,10,11',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'cn_name' => '江苏快3',
                'en_name' => 'jsk3',
                'series_id' => 'k3',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 41,
                'issue_format' => 'Ymd|N3',
                'issue_type' => 'day',
                'valid_code' => '1,2,3,4,5,6',
                'code_length' => 3,
                'positions' => 'w,q,b',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'cn_name' => '安徽快3',
                'en_name' => 'ahk3',
                'series_id' => 'k3',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 40,
                'issue_format' => 'Ymd|N3',
                'issue_type' => 'day',
                'valid_code' => '1,2,3,4,5,6',
                'code_length' => 3,
                'positions' => 'w,q,b',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'cn_name' => '甘肃快3',
                'en_name' => 'gsk3',
                'series_id' => 'k3',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 36,
                'issue_format' => 'Ymd|N3',
                'issue_type' => 'day',
                'valid_code' => '1,2,3,4,5,6',
                'code_length' => 3,
                'positions' => 'w,q,b',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'cn_name' => '河南快3',
                'en_name' => 'hnk3',
                'series_id' => 'k3',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 42,
                'issue_format' => 'Ymd|N3',
                'issue_type' => 'day',
                'valid_code' => '1,2,3,4,5,6',
                'code_length' => 3,
                'positions' => 'w,q,b',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'cn_name' => '中兴快3',
                'en_name' => 'zxk3',
                'series_id' => 'k3',
                'is_fast' => 1,
                'auto_open' => 1,
                'max_trace_number' => 60,
                'day_issue' => 1440,
                'issue_format' => 'Ymd|N4',
                'issue_type' => 'day',
                'valid_code' => '1,2,3,4,5,6',
                'code_length' => 3,
                'positions' => 'w,q,b',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'cn_name' => '福彩3D',
                'en_name' => 'fc3d',
                'series_id' => 'sd',
                'is_fast' => 0,
                'auto_open' => 0,
                'max_trace_number' => 20,
                'day_issue' => 1,
                'issue_format' => 'Y|T3',
                'issue_type' => 'increase',
                'valid_code' => '0,1,2,3,4,5,6,7,8,9',
                'code_length' => 3,
                'positions' => 'w,q,b',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'cn_name' => '中兴3D',
                'en_name' => 'zx3d',
                'series_id' => 'sd',
                'is_fast' => 1,
                'auto_open' => 1,
                'max_trace_number' => 20,
                'day_issue' => 1440,
                'issue_format' => 'Ymd|N4',
                'issue_type' => 'day',
                'valid_code' => '0,1,2,3,4,5,6,7,8,9',
                'code_length' => 3,
                'positions' => 'w,q,b',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'cn_name' => '上海时时乐',
                'en_name' => 'shssl',
                'series_id' => 'ssl',
                'is_fast' => 1,
                'auto_open' => 1,
                'max_trace_number' => 20,
                'day_issue' => 23,
                'issue_format' => 'Ymd-|N2',
                'issue_type' => 'day',
                'valid_code' => '0,1,2,3,4,5,6,7,8,9',
                'code_length' => 3,
                'positions' => 'b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'cn_name' => '排列35',
                'en_name' => 'p3p5',
                'series_id' => 'p3p5',
                'is_fast' => 0,
                'auto_open' => 0,
                'max_trace_number' => 20,
                'day_issue' => 1,
                'issue_format' => 'Y|T3',
                'issue_type' => 'increase',
                'valid_code' => '0,1,2,3,4,5,6,7,8,9',
                'code_length' => 5,
                'positions' => 'w,q,b,s,g',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'cn_name' => '北京PK10',
                'en_name' => 'bjpk10',
                'series_id' => 'pk10',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 44,
                'issue_format' => 'C6',
                'issue_type' => 'increase',
                'valid_code' => '1,2,3,4,5,6,7,8,9,10',
                'code_length' => 10,
                'positions' => '0,1,2,3,4,5,6,7,8,9',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'cn_name' => '急速飞艇',
                'en_name' => 'ftpk10',
                'series_id' => 'pk10',
                'is_fast' => 1,
                'auto_open' => 0,
                'max_trace_number' => 60,
                'day_issue' => 1440,
                'issue_format' => 'Ymd|N4',
                'issue_type' => 'day',
                'valid_code' => '1,2,3,4,5,6,7,8,9,10',
                'code_length' => 10,
                'positions' => '0,1,2,3,4,5,6,7,8,9',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'cn_name' => '中兴PK10',
                'en_name' => 'zxpk10',
                'series_id' => 'pk10',
                'is_fast' => 1,
                'auto_open' => 1,
                'max_trace_number' => 60,
                'day_issue' => 1440,
                'issue_format' => 'Ymd|N4',
                'issue_type' => 'day',
                'valid_code' => '1,2,3,4,5,6,7,8,9,10',
                'code_length' => 10,
                'positions' => '0,1,2,3,4,5,6,7,8,9',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'cn_name' => '香港六合彩',
                'en_name' => 'hklhc',
                'series_id' => 'lhc',
                'is_fast' => 0,
                'auto_open' => 0,
                'max_trace_number' => 1,
                'day_issue' => 1,
                'issue_format' => 'y|T3',
                'issue_type' => 'random',
                'valid_code' => '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49',
                'code_length' => 7,
                'positions' => '1,2,3,4,5,6,7',
                'min_prize_group' => 1700,
                'max_prize_group' => 1990,
                'min_times' => 1,
                'max_times' => 1000,
                'valid_modes' => '1,2,3',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}