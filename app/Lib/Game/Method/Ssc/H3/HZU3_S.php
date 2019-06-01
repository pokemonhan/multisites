<?php namespace App\Lib\Game\Method\Ssc\H3;

use App\Lib\Game\Method\Ssc\Base;


class HZU3_S extends Base
{
    // 112,221,311
    public $all_count = 90;
    public static $bzArr = array('000', '111', '222', '333', '444', '555', '666', '777', '888', '999');

    public static $filterArr = array(0 => 1, 1 => 1, 2 => 1, 3 => 1, 4 => 1, 5 => 1, 6 => 1, 7 => 1, 8 => 1, 9 => 1);

    //供测试用 生成随机投注
    public function randomCodes()
    {
        $rand = 2;
        $c = (array)array_rand(self::$filterArr, $rand);
        $c[] = $c[0];
        return implode('', $c);
    }

    public function fromOld($codes)
    {
        //112|223|343
        return implode(',', explode('|', $codes));
    }

    public function regexp($sCodes)
    {
        //格式
        if (!preg_match("/^(([0-9]{3}\,)*[0-9]{3})$/", $sCodes)) {
            return false;
        }

        //重复号码
        $temp = explode(",", $sCodes);
        $i = count(array_filter(array_unique($temp)));
        if ($i != count($temp)) return false;

        //豹子号
        if (count(array_intersect(self::$bzArr, $temp)) > 0) {
            return false;
        }

        $exists = [];
        //排除没有重复数字的
        foreach ($temp as $v) {
            $aNumber[0] = substr($v, 0, 1);
            $aNumber[1] = substr($v, 1, 1);
            $aNumber[2] = substr($v, 2, 1);
            if ($aNumber[0] != $aNumber[1] && $aNumber[1] != $aNumber[2] && $aNumber[0] != $aNumber[2]) {
                return false;
            }

            //组选不能重复号码
            $vv = $this->strOrder($v);
            if (isset($exists[$vv])) return false;
            $exists[$vv] = 1;
        }

        return true;
    }

    public function count($sCodes)
    {
        return count(explode(",", $sCodes));
    }

    //判定中奖
    public function assertLevel($levelId, $sCodes, Array $numbers)
    {
        //不限顺序
        $str = $this->strOrder(implode('', $numbers));
        $aCodes = explode(',', $sCodes);

        $flip = array_filter(array_count_values($numbers), function ($v) {
            return $v == 2;
        });

        if (count($flip) == 1) {
            foreach ($aCodes as $code) {
                if ($this->strOrder($code) === $str) {
                    return 1;
                }
            }
        }
    }
}