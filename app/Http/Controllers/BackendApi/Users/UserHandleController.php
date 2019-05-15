<?php

namespace App\Http\Controllers\BackendApi\Users;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\models\AuditFlow;
use App\models\HandleUserAccounts;
use App\models\PassworAuditLists;
use App\models\UserAdmitedFlowsModel;
use App\models\UserHandleModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserHandleController extends BackEndApiMainController
{
    protected $eloqM = 'UserHandleModel';

    /**
     * 创建总代时获取当前平台的奖金组
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserPrizeGroup()
    {
        $data['min'] = $this->currentPlatformEloq->prize_group_min;
        $data['max'] = $this->currentPlatformEloq->prize_group_max;
        return $this->msgOut(true, $data);
    }

    /**
     *创建总代与用户后台管理员操作创建
     */
    public function createUser()
    {
        $min = $this->currentPlatformEloq->prize_group_min;
        $max = $this->currentPlatformEloq->prize_group_max;
        $validator = Validator::make($this->inputs, [
            'username' => 'required|unique:users',
            'password' => 'required',
            'fund_password' => 'required',
            'is_tester' => 'required|numeric',
            'prize_group' => 'required|numeric|between:' . $min . ',' . $max,
            'type' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $this->inputs['nickname'] = $this->inputs['username'];
        $this->inputs['password'] = bcrypt($this->inputs['password']);
        $this->inputs['fund_password'] = bcrypt($this->inputs['fund_password']);
        $this->inputs['platform_id'] = $this->currentPlatformEloq->platform_id;
        $this->inputs['sign'] = $this->currentPlatformEloq->platform_sign;
        $this->inputs['vip_level'] = 0;
        $this->inputs['register_ip'] = request()->ip();
        DB::beginTransaction();
        try {
            $user = $this->eloqM::create($this->inputs);
            $user->rid = $user->id;
            $userAccountEloq = new HandleUserAccounts();
            $userAccountData = [
                'user_id' => $user->id,
                'balance' => 0,
                'frozen' => 0,
                'status' => 1,
            ];
            $userAccountEloq = $userAccountEloq->fill($userAccountData);
            $userAccountEloq->save();
            $user->account_id = $userAccountEloq->id;
            $user->save();
            DB::commit();
            $data['name'] = $user->username;
            return $this->msgOut(true, $data);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
//        $success['token'] = $user->createToken('前端')->accessToken;

    }

    //用户管理的所有用户信息表
    public function usersInfo()
    {
        //target model to join
        $fixedJoin = 1; //number of joining tables
        $withTable = 'account';
        $searchAbleFields = [
            'username',
            'type',
            'vip_level',
            'is_tester',
            'frozen_type',
            'prize_group',
            'level_deep',
            'register_ip',
        ];
        $withSearchAbleFields = ['balance'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields, $fixedJoin, $withTable,
            $withSearchAbleFields);
        return $this->msgOut(true, $data);
    }

    /**
     * 18.申请用户密码功能
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyResetUserPassword()
    {
        $rule = [
            'id' => 'required|numeric',
            'password' => 'required',
            'apply_note' => 'required',
        ];
        return $this->commonHandleUserPassword($rule, 1);
    }

    /**
     * 20.申请资金密码
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyResetUserFundPassword()
    {
        $rule = [
            'id' => 'required|numeric',
            'password' => 'required',
            'apply_note' => 'required',
        ];
        return $this->commonHandleUserPassword($rule, 2);
    }

    /**
     * 申请资金密码跟密码共用功能
     * @param $rule
     * @param $type todo if type new added then should notice on error message
     * @return \Illuminate\Http\JsonResponse
     */
    public function commonHandleUserPassword($rule, $type)
    {
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $applyUserEloq = $this->eloqM::find($this->inputs['id']);
        if (!is_null($applyUserEloq)) {
            $auditFlowEloq = new AuditFlow();
            $adminApplyEloq = new PassworAuditLists();
            //###################
            $adminApplyCheckEloq = $adminApplyEloq::where([
                ['user_id', '=', $applyUserEloq->id],
                ['status', '=', 0],
                ['type', '=', $type],
            ])->first();
            if (!is_null($adminApplyCheckEloq)) {
                if ($type === 1) {
                    $code = '100100';
                } else {
                    if ($type === 2) {
                        $code = '100101';
                    }
                }
                return $this->msgOut(false, [], $code);
            }
            //###################
            $flowData = [
                'admin_id' => $this->partnerAdmin->id,
                'admin_name' => $this->partnerAdmin->name,
                'username' => $applyUserEloq->username,
                'apply_note' => $this->inputs['apply_note'] ?? '',
            ];
            DB::beginTransaction();
            try {
                $auditResult = $auditFlowEloq->fill($flowData);
                $auditResult->save();
                $auditData = [
                    'type' => $type,
                    'user_id' => $applyUserEloq->id,
                    'audit_data' => Hash::make($this->inputs['password']),
                    'audit_flow_id' => $auditResult->id,
                    'status' => 0,
                ];
                $adminApplyResult = $adminApplyEloq->fill($auditData);
                $adminApplyResult->save();
                DB::commit();
                return $this->msgOut(true);
            } catch (\Exception $e) {
                DB::rollBack();
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100004');
        }
    }

    /**
     * 用户已申请的密码列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function appliedResetUserPasswordLists()
    {
        return $this->commonAppliedPasswordHandle();
    }

    /**
     * 用户资金密码已申请列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function appliedResetUserFundPasswordLists()
    {
        return $this->commonAppliedPasswordHandle();
    }

    private function commonAppliedPasswordHandle()
    {
        //main model
        $eloqM = $this->modelWithNameSpace('PassworAuditLists');
        //target model to join
        $fixedJoin = 1; //number of joining tables
        $withTable = 'auditFlow';
        $witTableCriterias = $withTable . ':id,admin_id,auditor_id,apply_note,auditor_note,updated_at,admin_name,auditor_name,username';
        $searchAbleFields = ['type', 'status', 'created_at', 'updated_at'];
        $withSearchAbleFields = ['username'];
        $data = $this->generateSearchQuery($eloqM, $searchAbleFields, $fixedJoin, $withTable, $withSearchAbleFields);
        return $this->msgOut(true, $data);
    }

    public function auditApplyUserPassword()
    {
        return $this->commonAuditPassword();
    }

    public function auditApplyUserFundPassword()
    {
        return $this->commonAuditPassword();
    }

    public function commonAuditPassword()
    {
        $rule = [
            'id' => 'required|numeric',
            'type' => 'required|numeric', //1登录密码 2 资金密码
            'status' => 'required|numeric', //1 通过 2 拒绝
            'auditor_note' => 'required', //密码审核备注
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $eloqM = $this->modelWithNameSpace('PassworAuditLists');
        $applyUserEloq = $eloqM::where([
            ['id', '=', $this->inputs['id']],
            ['type', '=', $this->inputs['type']],
            ['status', '=', 0],
        ])->first();
        if (!is_null($applyUserEloq)) {
            $auditFlowEloq = $applyUserEloq->auditFlow;
            //handle User
            $user = UserHandleModel::find($applyUserEloq->user_id);
            if ($applyUserEloq->type == 1) {
                $user->password = $applyUserEloq->audit_data;
            } else {
                $user->fund_password = $applyUserEloq->audit_data;
            }
            DB::beginTransaction();
            try {
                if ($this->inputs['status'] == 1) {
                    $user->save();
                }
                $auditFlowEloq->auditor_id = $this->partnerAdmin->id;
                $auditFlowEloq->auditor_note = $this->inputs['auditor_note'];
                $auditFlowEloq->auditor_name = $this->partnerAdmin->name;
                $auditFlowEloq->save();
                $applyUserEloq->status = $this->inputs['status'];
                $applyUserEloq->save();
                DB::commit();
                return $this->msgOut(true);
            } catch (\Exception $e) {
                DB::rollBack();
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100102');
        }
    }

    /**
     * 用户冻结账号功能
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivate()
    {
        $rule = [
            'user_id' => 'required|numeric',
            'frozen_type' => 'required|numeric', //冻结类型
            'comment' => 'required', //备注
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $userEloq = $this->eloqM::find($this->inputs['user_id']);
        if (!is_null($userEloq)) {
            DB::beginTransaction();
            try {
                $userEloq->frozen_type = $this->inputs['frozen_type'];
                $userEloq->save();
                $userAdmitFlowLog = new UserAdmitedFlowsModel();
                $data = [
                    'admin_id' => $this->partnerAdmin->id,
                    'admin_name' => $this->partnerAdmin->name,
                    'user_id' => $userEloq->id,
                    'username' => $userEloq->username,
                    'comment' => $this->inputs['comment'],
                ];
                $userAdmitFlowLog->fill($data);
                $userAdmitFlowLog->save();
                DB::commit();
                return $this->msgOut(true);
            } catch (\Exception $e) {
                DB::rollBack();
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        }
    }

    public function deactivateDetail()
    {
        $rule = [
            'user_id' => 'required|numeric',
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors());
        }
        $userEloq = $this->eloqM::find($this->inputs['user_id']);
        if (!is_null($userEloq)) {
            $data = $userEloq->userAdmitedFlow->toArray();
            return $this->msgOut(true, $data);
        }

    }

}