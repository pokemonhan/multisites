<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-24 21:17:56
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 21:26:48
 */
namespace App\Http\SingleActions\Backend\Users;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Lib\Common\AccountChange;
use App\Models\User\FrontendUser;
use App\Models\User\Fund\AccountChangeReport;
use App\Models\User\Fund\AccountChangeType;
use App\Models\User\Fund\FrontendUsersAccount;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserHandleDeductionBalanceAction
{
    protected $model;

    /**
     * @param  FrontendUser  $frontendUser
     */
    public function __construct(FrontendUser $frontendUser)
    {
        $this->model = $frontendUser;
    }

    /**
     * 人工扣除用户资金
     * @param  BackEndApiMainController  $contll
     * @param  $inputDatas
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        //人工扣款的帐变类型表
        $accountChangeTypeEloq = AccountChangeType::select('name', 'sign')->where('sign', 'artificial_deduction')->first();
        if ($accountChangeTypeEloq === null) {
            return $contll->msgOut(false, [], '100103');
        }
        $userAccountsEloq = FrontendUsersAccount::where('user_id', $inputDatas['user_id'])->first();
        if ($userAccountsEloq->balance < $inputDatas['amount']) {
            return $contll->msgOut(false, [], '100104');
        }
        DB::beginTransaction();
        try {
            //扣除金额
            $newBalance = $userAccountsEloq->balance - $inputDatas['amount'];
            $editArr = ['balance' => $newBalance];
            $editStatus = FrontendUsersAccount::where('user_id', $inputDatas['user_id'])->where('updated_at', $userAccountsEloq->updated_at)->update($editArr);
            if ($editStatus === 0) {
                return $contll->msgOut(false, [], '100105');
            }
            //添加帐变记录
            $userEloq = $this->model::select('id', 'sign', 'top_id', 'parent_id', 'rid', 'username')->where('id', $inputDatas['user_id'])->first();
            $accountChangeReportEloq = new AccountChangeReport();
            $accountChangeObj = new AccountChange();
            $accountChangeObj->addData($accountChangeReportEloq, $userEloq, $inputDatas['amount'], $userAccountsEloq->balance, $newBalance, $accountChangeTypeEloq);
            DB::commit();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $contll->msgOut(false, [], $sqlState, $msg);
        }
    }
}