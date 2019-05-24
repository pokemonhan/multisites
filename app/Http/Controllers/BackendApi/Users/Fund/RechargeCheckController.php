<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\FundOperationRecharge;
use App\Models\AccountChangeReport;
use App\Models\AccountChangeType;
use App\Models\AuditFlow;
use App\Models\FundOperation;
use App\Models\HandleUserAccounts;
use App\Models\UserHandleModel;
use App\Models\UserRechargeHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RechargeCheckController extends BackEndApiMainController
{
    protected $eloqM = 'ArtificialRechargeLog';

    public function detail()
    {
        $fixedJoin = 1;
        $withTable = 'auditFlow';
        $withSearchAbleFields = ['apply_note'];
        $searchAbleFields = ['status', 'type'];
        $orderFields = 'id';
        $orderFlow = 'desc';
        $this->inputs['type'] = 2;
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields, $orderFields, $orderFlow);
        return $this->msgOut(true, $data);
    }

    public function auditSuccess()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'auditor_note' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        // 审核表
        $RechargeLog = $this->eloqM::find($this->inputs['id']);
        $auditFlow = auditFlow::where('id', $RechargeLog->audit_flow_id)->first();
        if ($RechargeLog->status !== 0) {
            return $this->msgOut(false, [], '100900');
        }
        //检查是否存在 人工充值 的帐变类型表
        $accountChangeTypeEloq = AccountChangeType::select('name', 'sign')->where('sign', 'ArtificialRecharge')->first();
        if (is_null($accountChangeTypeEloq)) {
            return $this->msgOut(false, [], '100901');
        }
        DB::beginTransaction();
        try {
            // 修改 artificial_recharge_log 表 的审核状态
            $RechargeLogEdit = ['status' => $RechargeLog::AUDITSUCCESS];
            $RechargeLog->fill($RechargeLogEdit);
            $RechargeLog->save();
            // 修改 user_recharge_history 表 的审核状态
            $historyEloq = UserRechargeHistory::where('audit_flow_id', $RechargeLog->audit_flow_id)->first();
            $historyEdit = ['status' => $historyEloq::AUDITSUCCESS];
            $historyEloq->fill($historyEdit);
            $historyEloq->save();
            //用户金额表
            $userData = UserHandleModel::where('id', $RechargeLog->user_id)->with('account')->first();
            $balance = $userData->account->balance + $RechargeLog['amount'];
            $UserAccountsEdit = ['balance' => $balance];
            $this->auditFlowEdit($auditFlow, $this->partnerAdmin, $this->inputs['auditor_note']);
            $UserAccounts = HandleUserAccounts::where('user_id', $RechargeLog->user_id)->first();
            HandleUserAccounts::where(function ($query) use ($UserAccounts) {
                $query->where('user_id', $UserAccounts->user_id)
                    ->where('updated_at', $UserAccounts->updated_at);
            })->update($UserAccountsEdit);
            //用户帐变表
            $this->insertChangeReport($userData, $RechargeLog['amount'], $balance, $accountChangeTypeEloq);
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function auditFailure()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'auditor_note' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $RechargeLog = $this->eloqM::find($this->inputs['id']);
        if ($RechargeLog->status !== 0) {
            return $this->msgOut(false, [], '100900');
        }
        $adminFundData = FundOperation::where('admin_id', $RechargeLog->admin_id)->first();
        $newFund = $adminFundData->fund + $RechargeLog->amount;
        DB::beginTransaction();
        try {
            // 修改 artificial_recharge_log 表 的审核状态
            $RechargeLogEdit = ['status' => $RechargeLog::AUDITFAILURE];
            $RechargeLog->fill($RechargeLogEdit);
            $RechargeLog->save();
            // 修改 user_recharge_history 表 的审核状态
            $historyEloq = UserRechargeHistory::where('audit_flow_id', $RechargeLog->audit_flow_id)->first();
            $historyEdit = ['status' => $historyEloq::AUDITFAILURE];
            $historyEloq->fill($historyEdit);
            $historyEloq->save();
            //退还管理员人工充值额度
            $auditFlow = auditFlow::where('id', $RechargeLog->audit_flow_id)->first();
            $adminFundDataEdit = ['fund' => $newFund];
            $this->auditFlowEdit($auditFlow, $this->partnerAdmin, $this->inputs['auditor_note']);
            $adminFundData->fill($adminFundDataEdit);
            $adminFundData->save();
            //返还额度后  插入artificial_recharge_log 记录表
            $RechargeLogeloqM = new $this->eloqM;
            $type = $RechargeLogeloqM::SYSTEM;
            $in_out = $RechargeLogeloqM::INCREMENT;
            $comment = '[充值审核失败额度返还]==>+' . $RechargeLog['amount'] . '|[目前额度]==>' . $newFund;
            $fundOperationClass = new FundOperationRecharge();
            $fundOperationClass->insertOperationDatas($RechargeLogeloqM, $type, $in_out, null, null, $auditFlow->admin_id, $auditFlow->admin_name, $RechargeLog->amount, $comment, null);
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function auditFlowEdit($eloq, $partnerAdmin, $auditor_note)
    {
        $editData = [
            'auditor_id' => $partnerAdmin->id,
            'auditor_name' => $partnerAdmin->name,
            'auditor_note' => $auditor_note,
        ];
        $eloq->fill($editData);
        $eloq->save();
    }

    public function insertChangeReport($user, $amount, $balance, $type)
    {
        $insertData = [
            'sign' => $user->sign,
            'user_id' => $user['id'],
            'top_id' => $user['top_id'],
            'parent_id' => $user['parent_id'],
            'rid' => $user['rid'],
            'username' => $user['username'],
            'type_sign' => $type->sign,
            'type_name' => $type->name,
            'amount' => $amount,
            'before_balance' => $user['account']['balance'],
            'balance' => $balance,
        ];
        $eloq = new AccountChangeReport();
        $eloq->fill($insertData);
        $eloq->save();
    }
}
