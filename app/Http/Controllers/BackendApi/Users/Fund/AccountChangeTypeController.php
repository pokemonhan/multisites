<?php

namespace App\Http\Controllers\BackendApi\Users\Fund;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeAddRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeDeleteRequest;
use App\Http\Requests\Backend\Users\Fund\AccountChangeTypeEditRequest;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeAddAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeDeleteAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeDetailAction;
use App\Http\SingleActions\Backend\Users\Fund\AccountChangeTypeEditAction;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeController extends BackEndApiMainController
{
    protected $eloqM = 'User\Fund\AccountChangeType';

    /**
     * 帐变类型列表
     * @param   AccountChangeTypeDetailAction $action
     * @return  JsonResponse
     */
    public function detail(AccountChangeTypeDetailAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 添加帐变类型
     * @param   AccountChangeTypeAddRequest $request
     * @param   AccountChangeTypeAddAction  $action
     * @return  JsonResponse
     */
    public function add(AccountChangeTypeAddRequest $request, AccountChangeTypeAddAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 编辑帐变类型
     * @param   AccountChangeTypeEditRequest $request
     * @param   AccountChangeTypeEditAction  $action
     * @return  JsonResponse
     */
    public function edit(AccountChangeTypeEditRequest $request, AccountChangeTypeEditAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 删除帐变类型
     * @param   AccountChangeTypeDeleteRequest $request
     * @param   AccountChangeTypeDeleteAction  $action
     * @return  JsonResponse
     */
    public function delete(AccountChangeTypeDeleteRequest $request, AccountChangeTypeDeleteAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}
