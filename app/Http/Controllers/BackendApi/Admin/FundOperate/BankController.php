<?php

namespace App\Http\Controllers\BackendApi\Admin\FundOperate;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BankController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Fund\FrontendSystemBank';

    //银行列表
    public function detail(): JsonResponse
    {
        $searchAbleFields = ['title', 'code', 'pay_type', 'status'];
        $banksDatas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $banksDatas);
    }

    //添加银行
    public function addBank(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'title' => 'required|string|unique:frontend_system_banks,title',
            'code' => 'required|alpha',
            'pay_type' => 'required|numeric',
            'status' => 'required|in:0,1',
            'min_recharge' => 'required|numeric',
            'max_recharge' => 'required|numeric',
            'min_withdraw' => 'required|numeric',
            'max_withdraw' => 'required|numeric',
            'remarks' => 'required|string',
            'allow_user_level' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $addDatas = $this->inputs;
        try {
            $configure = new $this->eloqM();
            $configure->fill($addDatas);
            $configure->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //编辑银行
    public function editBank(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_system_banks,id',
            'title' => 'required|string',
            'code' => 'required|alpha',
            'status' => 'required|in:0,1',
            'min_recharge' => 'required|numeric',
            'max_recharge' => 'required|numeric',
            'min_withdraw' => 'required|numeric',
            'max_withdraw' => 'required|numeric',
            'remarks' => 'required|string',
            'allow_user_level' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastEloq = $this->eloqM::find($this->inputs['id']);
        $this->editAssignment($pastEloq, $this->inputs);
        try {
            $pastEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除银行
    public function deleteBank(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric|exists:frontend_system_banks,id',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        try {
            $this->eloqM::where('id', $this->inputs['id'])->delete();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
