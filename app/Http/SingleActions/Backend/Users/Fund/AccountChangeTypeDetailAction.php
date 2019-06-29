<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-24 19:48:37
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 19:50:31
 */
namespace App\Http\SingleActions\Backend\Users\Fund;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\User\Fund\FrontendUserAccountType;
use Illuminate\Http\JsonResponse;

class AccountChangeTypeDetailAction
{
    protected $model;

    /**
     * @param  FrontendUserAccountType  $frontendUserAccountType
     */
    public function __construct(FrontendUserAccountType $frontendUserAccountType)
    {
        $this->model = $frontendUserAccountType;
    }

    /**
     * 帐变类型列表
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll): JsonResponse
    {
        $searchAbleFields = ['name', 'sign', 'in_out', 'type'];
        $datas = $contll->generateSearchQuery($this->model, $searchAbleFields);
        return $contll->msgout(true, $datas);
    }
}
