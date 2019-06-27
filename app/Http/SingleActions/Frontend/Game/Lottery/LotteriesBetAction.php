<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-27 18:03:02
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-27 18:07:21
 */
namespace App\Http\SingleActions\Frontend\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Lib\Locker\AccountLocker;
use App\Lib\Logic\AccountChange;
use App\Models\Game\Lottery\LotteryIssue;
use App\Models\Game\Lottery\LotteryList;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LotteriesBetAction
{
    /**
     * 游戏-投注
     * @param  FrontendApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll, $inputDatas): JsonResponse
    {
        $usr = $contll->currentAuth->user();
        $lotterySign = $inputDatas['lottery_sign'];
        $lottery = LotteryList::getLottery($lotterySign);
        $betDetail = [];
        $_totalCost = 0;
        $singleCost = 0;
        // 初次解析
        $_balls = [];
        foreach ($inputDatas['balls'] as $item) {
            $methodId = $item['method_id'];
            $method = $lottery->getMethod($methodId);
            $validator = Validator::make($method, [
                'status' => 'required|in:1', //玩法状态
                'object' => 'required', //玩法对象
                'method_name' => 'required', // 玩法未定义
            ]);
            if ($validator->fails()) {
                return $contll->msgOut(false, [], '400', $validator->errors()->first());
            }
            $oMethod = $method['object']; // 玩法 - 对象
            // 转换格式
            $project['codes'] = $oMethod->resolve($oMethod->parse64($item['codes']));

            if ($oMethod->supportExpand) {
                $position = [];
                if (isset($item['position'])) {
                    $position = (array) $item['position'];
                }
                if (!$oMethod->checkPos($position) || 1 != 2) {
                    return $contll->msgOut(false, [], '100300', '', 'methodName', $oMethod->name);
                }
                $expands = $oMethod->expand($item['codes'], $position);
                foreach ($expands as $expand) {
                    $item['method_id'] = $expand['method_id'];
                    $item['codes'] = $expand['codes'];
                    $item['count'] = $expand['count'];
                    $item['cost'] = $item['mode'] * $item['times'] * $item['price'];
                    $_balls[] = $item;
                }
            } else {
                $_balls[] = $item;
            }
        }
        $inputDatas['balls'] = $_balls;
        foreach ($inputDatas['balls'] as $item) {
            $methodId = $item['method_id'];
            $method = $lottery->getMethod($methodId);
            $oMethod = $method['object'];
            // 模式
            $mode = $item['mode'];
            $modes = config('game.main.modes_array');
            if (!in_array($mode, $modes)) {
                return $contll->msgOut(false, [], '100301', '', 'mode', $mode);
            }
            // 奖金组 - 游戏
            $prizeGroup = (int) $item['prize_group'];
            if (!$lottery->isValidPrizeGroup($prizeGroup)) {
                return $contll->msgOut(false, [], '100302', '', 'prizeGroup', $prizeGroup);
            }
            // 奖金组 - 用户
            if ($usr->prize_group < $prizeGroup) {
                return $contll->msgOut(false, [], '100303', '', 'prizeGroup', $prizeGroup);
            }
            // 投注号码
            $ball = $item['codes'];
            if (!$oMethod->regexp($ball)) {
                return $contll->msgOut(false, [], '100304', '', 'methodId', $methodId);
            }
            // 倍数
            $times = (int) $item['times'];
            if (!$lottery->isValidTimes($times)) {
                return $contll->msgOut(false, [], '100305', '', 'times', $times);
            }
            // 单价花费
            $singleCost = $mode * $times * $item['price'] * $item['count'];
            if ($singleCost !== $item['cost']) {
                return $contll->msgOut(false, [], '100306');
            }
            $_totalCost += $singleCost;
            $betDetail[] = [
                'method_id' => $methodId,
                'method_name' => $method['method_name'],
                'mode' => $mode,
                'prize_group' => $prizeGroup,
                'times' => $times,
                'price' => $item['price'],
                'total_price' => $singleCost,
                'code' => $ball,
            ];
        }
        if ((int) $inputDatas['is_trace'] === 1) {
            $i = 0;
            foreach ($inputDatas['trace_issues'] as $traceMultiple) {
                if ($i++ < 1) {
                    continue;
                }
                $_totalCost += $traceMultiple * $singleCost;
            }
        }
        if ($_totalCost !== (int) $inputDatas['total_cost']) {
            return $contll->msgOut(false, [], '100307');
        }
        // 投注期号
        $traceData = array_keys($inputDatas['trace_issues']);
        // 检测追号奖期
        if (!$traceData || !is_array($traceData)) {
            return $contll->msgOut(false, [], '100308');
        }
        $traceDataCollection = $lottery->checkTraceData($traceData);
        if (count($traceData) !== $traceDataCollection->count()) {
            return $contll->msgOut(false, [], '100309');
        }
        // 获取当前奖期 @todo 判断过期 还是其他期
        $currentIssue = LotteryIssue::getCurrentIssue($lottery->en_name);
        if (!$currentIssue) {
            return $contll->msgOut(false, [], '100310');
        }
        // 奖期和追号
        /*if ($currentIssue->issue != $traceData[0]) {
        return $this->msgOut(false, [], '', '对不起, 奖期已过期!');
        }*/
        $accountLocker = new AccountLocker($usr->id);
        if (!$accountLocker->getLock()) {
            return $contll->msgOut(false, [], '100311');
        }
        $account = $usr->account()->first();
        if ($account->balance < $_totalCost) {
//不知道 $totalcost * 10000 所以去掉了
            $accountLocker->release();
            return $contll->msgOut(false, [], '100312');
        }
        DB::beginTransaction();
        try {
            if ((int) $inputDatas['is_trace'] === 1 && count($inputDatas['trace_issues']) > 1) {
                $traceData = array_slice($inputDatas['trace_issues'], 1, null, true);
            } else {
                $traceData = [];
            }
            $from = $inputDatas['from'] ?? 1; //手机端 还是 pc 端
            $data = Project::addProject($usr, $lottery, $currentIssue, $betDetail, $traceData, $from);
            // 帐变
            $accountChange = new AccountChange();
            $accountChange->setReportMode(AccountChange::MODE_REPORT_AFTER);
            $accountChange->setChangeMode(AccountChange::MODE_CHANGE_AFTER);
            foreach ($data['project'] as $item) {
                $params = [
                    'user_id' => $usr->id,
                    'amount' => $item['cost'],
                    'lottery_id' => $item['lottery_id'],
                    'method_id' => $item['method_id'],
                    'project_id' => $item['id'],
                    'issue' => $currentIssue->issue,
                ];
                $res = $accountChange->doChange($account, 'bet_cost', $params);
                if ($res !== true) {
                    DB::rollBack();
                    $accountLocker->release();
                    return $contll->msgOut(false, [], '', '对不起, ' . $res);
                }
            }
            $accountChange->triggerSave();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $accountLocker->release();
            Log::info('投注-异常:' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine()); //Clog::userBet
            return $contll->msgOut(false, [], '', '对不起, ' . $e->getMessage() . '|' . $e->getFile() . '|' . $e->getLine());
        }
        $accountLocker->release();
        return $contll->msgOut(true, $data);
    }
}
