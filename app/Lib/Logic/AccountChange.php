<?php
namespace App\Lib\Logic;

use App\Lib\Clog;
use App\Models\User\Fund\AccountChangeReport;
use App\Models\User\Fund\AccountChangeType;
use App\Models\User\UserHandleModel;
use Illuminate\Support\Facades\DB;

/**
 * 帐变主逻辑
 * Class AccountChange
 * @package App\Lib\Moon
 */
class AccountChange
{
    public const FROZEN_STATUS_OUT         = 1;
    public const FROZEN_STATUS_BACK        = 2;
    public const FROZEN_STATUS_TO_PLAYER   = 3;
    public const FROZEN_STATUS_TO_SYSTEM   = 4;
    public const FROZEN_STATUS_BONUS       = 5;

    public const MODE_CHANGE_AFTER         = 2;
    public const MODE_CHANGE_NOW           = 1;

    public const MODE_REPORT_AFTER         = 2;
    public const MODE_REPORT_NOW           = 1;

    public  $reportMode             = 1;
    public  $changeMode             = 1;

    public  $changes                = [];
    public  $reports                = [];

    public  $accounts               = [];

    // 设置报表保存模式
    function setReportMode($mode) {
        $this->reportMode = $mode;
    }

    // 设置帐变保存模式
    function setChangeMode($mode) {
        $this->changeMode = $mode;
    }

    /**
     * @param $account
     * @param $type
     * @param $params
     * @return bool|string
     * @throws \Exception
     */
    public function change($account, $type, $params)
    {
        try {
            $this->accounts[$account->user_id] = $account;
            return $this->doChange($account, $type, $params);
        }catch (\Exception $e){
            Clog::account('error-'. $e->getMessage() .'|'. $e->getLine() .'|'. $e->getFile());
            return $e->getMessage();
        }
    }

    /**
     * @param $account
     * @param $typeSign
     * @param $params
     * @return bool|string
     * @throws \Exception
     */
    public function doChange($account, $typeSign, $params)
    {
        $user       = $account->user()->first();
        $typeConfig = AccountChangeType::getTypeBySign($typeSign);

        //　1. 获取帐变配置
        if (empty($typeConfig)) {
            Clog::account("error-{$user->id}-{$typeSign}不存在!");
            return "对不起, {$typeSign}不存在!";
        }
        // 2. 参数检测
        foreach ($typeConfig as $key => $value) {
            if (in_array($key, ['id', 'name', 'sign', 'in_out', 'frozen_type'])) {
                continue;
            }
            if ($value == 1) {
                if (!isset($params[$key])) {
                    return "对不起, 参数{$key}没有传递!";
                }
            }
        }
        // 3. 检测金额
        $amount = abs($params['amount']);
        if ($amount == 0) {
            return true;
        }
        // 4. 关联用户是否存在
        $relatedUser = null;
        if (isset($params['related_id'])) {
            $relatedUser = UserHandleModel::findByCache($params['related_id']);
            if (!$relatedUser) {
                return '对不起, 无效的关联用户!';
            }
        }
        // 冻结类型 1 冻结自己金额 2 冻结退还　3 冻结给玩家　4 冻结给系统　5 中奖
        // 资金增减. 需要检测对应
        if ( $typeConfig['frozen_type'] == 5) {
            if (!$relatedUser) {
                return '对不起, 必须存在关联用户!';
            }
            $relatedAccount = $relatedUser->account()->first();
            $frozen         = $relatedAccount->frozen;
            if ($frozen < $amount) {
                return '对不起, 相关用户可用冻结金额不足!';
            }
        }
        // 保存记录
        $report = [
            'sign'          => $user->sign,
            'user_id'       => $user->id,
            'top_id'        => $user->top_id,
            'parent_id'     => $user->parent_id,
            'rid'           => $user->rid,
            'username'      => $user->username,
            'is_tester'     => $user->is_tester,

            'type_sign'         => $typeConfig['sign'],
            'type_name'         => $typeConfig['name'],

            'project_id'        => $params['project_id'] ?? 0,
            'lottery_id'        => $params['lottery_id'] ?? 0,
            'method_id'         => $params['method_id'] ?? 0,
            'issue'             => $params['issue'] ?? 0,
            'from_id'           => $params['from_id'] ?? 0,
            'from_admin_id'     => $params['from_admin_id'] ?? 0,
            'to_id'             => $params['to_id'] ?? 0,
            'activity_sign'     => $params['activity_sign'] ?? 0,
            'desc'              => $params['desc'] ?? 0,

            'frozen_type'       => $typeConfig['frozen_type'],
            'day'               => date('Ymd'),
            'amount'            => $amount,
            'process_time'      => time(),
            'created_at'        => date('Y-m-d H:i:s'),
        ];
        $beforeBalance      = $account->balance;
        $beforeFrozen       = $account->frozen;
        // 根据冻结类型处理
        switch($typeConfig['frozen_type']) {
            case self::FROZEN_STATUS_OUT:
                $ret = $this->frozen($account, $amount);
                break;
            case self::FROZEN_STATUS_BACK:
                $ret = $this->unFrozen($account, $amount);
                break;
            case self::FROZEN_STATUS_TO_PLAYER:
            case self::FROZEN_STATUS_TO_SYSTEM:
                $ret = $this->unFrozenToPlayer($account, $amount);
                break;
            default:
                if ($typeConfig['in_out'] == 1) {
                    $ret = $this->add($account, $amount);
                } else {
                    $ret = $this->cost($account, $amount);
                }
        }
        if ($ret !== true) {
            return "对不起, 账户异常({$ret})!";
        }
        $balance    = $account->balance;
        $frozen     = $account->frozen;
        $report['before_balance']   = $beforeBalance;
        $report['balance']          = $balance;
        $report['frozen_balance']   = $frozen;
        $report['before_frozen_balance']    = $beforeFrozen;
        $change['updated_at']       = date('Y-m-d H:i:s');
        $this->saveReportData($report);
        return true;
    }

    // 资金增加
    public function add($account, $money)
    {
        if ($this->changeMode == self::MODE_CHANGE_AFTER) {
            if (isset($this->changes[$account->user_id])) {
                if (isset($this->changes[$account->user_id]['add'])) {
                    $this->changes[$account->user_id]['add'] += $money;
                } else {
                    $this->changes[$account->user_id]['add'] = $money;
                }
            } else {
                $this->changes[$account->user_id] = [];
                $this->changes[$account->user_id]['add'] = $money;
            }
            $account->balance += $money;
            return true;
        } else {
            $updated_at = date('Y-m-d H:i:s');
            $sql = "update `user_accounts` set `balance`=`balance`+'{$money}' , `updated_at`='$updated_at'  where `user_id` ='{$account->user_id}'";
            $ret= DB::update($sql) > 0 ;
            if($ret){
                $account->balance += $money;
            }
            return $ret;
        }
    }

    // 消耗资金
    public function cost($account, $money)
    {
        if ($money > $account->balance) {
            return '对不起, 用户余额不足!';
        }
        if ($this->changeMode == self::MODE_CHANGE_AFTER) {
            if (isset($this->changes[$account->user_id])) {
                if (isset($this->changes[$account->user_id]['cost'])) {
                    $this->changes[$account->user_id]['cost'] += $money;
                } else {
                    $this->changes[$account->user_id]['cost'] = $money;
                }
            } else {
                $this->changes[$account->user_id] = [];
                $this->changes[$account->user_id]['cost'] = $money;
            }
            $account->balance -= $money;
            return true;
        } else {
            $updated_at = date('Y-m-d H:i:s');
            $ret= DB::update("update `user_accounts` set `balance`=`balance`-'{$money}' , `updated_at`='$updated_at'  where `user_id` ='{$account->user_id}' and `balance`>='{$money}'") > 0 ;
            if($ret){
                $account->balance = $account->balance - $money;
            }
            return $ret;
        }
    }

    // 冻结资金
    public function frozen($account, $money)
    {
        if ($money > $account->balance) {
            return '对不起, 用户余额不足!';
        }
        if ($this->changeMode == self::MODE_CHANGE_AFTER) {
            if (isset($this->changes[$account->user_id])) {
                if (isset($this->changes[$account->user_id]['frozen'])) {
                    $this->changes[$account->user_id]['frozen'] += $money;
                } else {
                    $this->changes[$account->user_id]['frozen'] = $money;
                }
            } else {
                $this->changes[$account->user_id] = [];
                $this->changes[$account->user_id]['frozen'] = $money;
            }
            $account->balance -= $money;
            $account->frozen += $money;
            return true;
        } else {
            $updated_at = date('Y-m-d H:i:s');
            $ret = DB::update("update `user_accounts` set `balance`=`balance`-'{$money}', `frozen`=`frozen`+ '{$money}'  , `updated_at`='$updated_at' where `user_id` ='{$account->user_id}' and `balance`>='{$money}'") > 0;
            if ($ret) {
                $account->balance -= $money;
                $account->frozen += $money;
            }
            return $ret;
        }
    }

    // 解冻
    public function unFrozen($account, $money)
    {
        if ($this->changeMode == self::MODE_CHANGE_AFTER) {
            if (isset($this->changes[$account->user_id])) {
                if (isset($this->changes[$account->user_id]['unFrozen'])) {
                    $this->changes[$account->user_id]['unFrozen'] += $money;
                } else {
                    $this->changes[$account->user_id]['unFrozen'] = $money;
                }
            } else {
                $this->changes[$account->user_id] = [];
                $this->changes[$account->user_id]['unFrozen'] = $money;
            }
            $account->balance += $money;
            $account->frozen -= $money;
            return true;
        } else {
            $updated_at = date('Y-m-d H:i:s');

            $ret = DB::update("update `user_accounts` set `balance`=`balance`+'{$money}', `frozen`=`frozen`- '{$money}' , `updated_at`='$updated_at'  where `user_id` ='{$account->user_id}'") > 0;

            if ($ret) {
                $account->balance += $money;
                $account->frozen -= $money;
            }
            return $ret;
        }
    }

    // 解冻 - 到其他玩家头上
    public function unFrozenToPlayer($account, $money)
    {
        if ($money > $account->frozen) {
            Clog::account("error-{$account->user_id}-{$money}-{$account->frozen}-冻结金额不足!");
            return '对不起, 用户冻结金额不足!';
        }

        if ($this->changeMode == self::MODE_CHANGE_AFTER) {
            if (isset($this->changes[$account->user_id])) {
                if (isset($this->changes[$account->user_id]['unFrozenToPlayer'])) {
                    $this->changes[$account->user_id]['unFrozenToPlayer'] += $money;
                } else {
                    $this->changes[$account->user_id]['unFrozenToPlayer'] = $money;
                }
            } else {
                $this->changes[$account->user_id] = [];
                $this->changes[$account->user_id]['unFrozenToPlayer'] = $money;
            }
            $account->frozen -= $money;
            return true;
        } else {
            $updated_at = date('Y-m-d H:i:s');
            $ret = DB::update("update `user_accounts` set  `frozen`=`frozen`- '{$money}' , `updated_at`='$updated_at'  where `user_id` ='{$account->user_id}'") > 0;
            if ($ret) {
                $account->frozen -= $money;
            }
            return $ret;
        }
    }

    /**
     * 存储
     * @return bool
     */
    public function triggerSave() {
        // 报表保存
        if ($this->reports) {
            $ret = AccountChangeReport::insert( $this->reports );
            if(!$ret) {
                return false;
            }
            $this->reports = [];
        }
        // 帐变保存
        if ($this->changes) {
            foreach ($this->changes as $userId => $_data) {
                $balanceAdd     = 0;
                $frozenAdd      = 0;

                foreach ($_data as $_key => $amount) {
                    switch ($_key) {
                        case 'add':
                            $balanceAdd += $amount;
                            break;
                        case 'cost':
                            $balanceAdd -= $amount;
                            break;
                        case 'frozen':
                            $balanceAdd -= $amount;
                            $frozenAdd  += $amount;
                            break;
                        case 'unfrozen':
                            $balanceAdd += $amount;
                            $frozenAdd  -= $amount;
                            break;
                        case 'unFrozenToPlayer':
                            $frozenAdd  -= $amount;
                            break;
                        default :
                            break;
                    }
                }
                if ($balanceAdd === 0 && $frozenAdd === 0) {
                    continue;
                }
                $sql = 'update `user_accounts` set ';
                // 冻结金额
                if ($frozenAdd > 0) {
                    $sql .= " `frozen`=`frozen` + '{$frozenAdd}',";
                } else if ($frozenAdd < 0) {
                    $frozenAdd  = abs($frozenAdd);
                    $sql .= " `frozen`=`frozen` - '{$frozenAdd}',";
                }
                // 资金
                if ($balanceAdd > 0) {
                    $sql .= " `balance`=`balance` + '{$balanceAdd}',";
                } else if ($balanceAdd < 0) {
                    $balanceAdd = abs($balanceAdd);
                    $sql .= " `balance`=`balance` - '{$balanceAdd}',";
                }
                // 更新时间
                $updated_at = date('Y-m-d H:i:s');
                $sql .= " `updated_at`='$updated_at'  where `user_id` ='{$userId}'";
                $ret = DB::update($sql);
                if (!$ret) {
                    return false;
                }
            }
            $this->changes  = [];
            $this->accounts = [];
        }
        return true;
    }

    /**
     * 保存记录
     * @param $report
     * @return bool
     */
    public function saveReportData($report) {
        if ($this->reportMode == self::MODE_REPORT_AFTER) {
            $this->reports[] = $report;
        } else {
            $ret = AccountChangeReport::insert( $report );
            if(!$ret) {
                return false;
            }
        }
        return true;
    }
}
