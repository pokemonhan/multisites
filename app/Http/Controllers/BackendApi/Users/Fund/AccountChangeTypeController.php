<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeAddRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeDeleteRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeEditRequest;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeController extends BackEndApiMainController
{
    protected $eloqM = 'User\Fund\AccountChangeType';

    //帐变类型列表
    public function detail(): JsonResponse
    {
        $searchAbleFields = ['name', 'sign', 'in_out', 'type'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgout(true, $datas);
    }

    //添加帐变类型
    public function add(AccountChangeTypeAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        try {
            $eloqM = new $this->eloqM;
            $eloqM->fill($inputDatas);
            $eloqM->save();
            return $this->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //编辑帐变类型
    public function edit(AccountChangeTypeEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastEloq = $this->eloqM::find($inputDatas['id']);
        $checkData = $this->eloqM::where(function ($query) {
            $query->where('sign', $inputDatas['sign'])->where('id', '!=', $inputDatas['id']);
        })->first();
        if (!is_null($checkData)) {
            return $this->msgout(false, [], '101200');
        }
        try {
            $this->editAssignment($pastEloq, $inputDatas);
            $pastEloq->save();
            return $this->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除帐变类型
    public function delete(AccountChangeTypeDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastEloq = $this->eloqM::find($inputDatas['id']);
        try {
            $pastEloq->delete();
            return $this->msgout(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
