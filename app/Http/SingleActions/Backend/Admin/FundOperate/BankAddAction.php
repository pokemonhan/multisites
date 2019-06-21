<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-20 20:10:10
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-21 21:15:32
 */
namespace App\Http\SingleActions\Backend\Admin\FundOperate;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\Admin\Fund\FrontendSystemBank;
use Exception;
use Illuminate\Http\JsonResponse;

class BankAddAction
{
    protected $model;

    /**
     * @param  FrontendSystemBank  $frontendSystemBank
     */
    public function __construct(FrontendSystemBank $frontendSystemBank)
    {
        $this->model = $frontendSystemBank;
    }

    /**
     * 添加银行
     * @param  BackEndApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        try {
            $configure = new $this->model();
            $configure->fill($inputDatas);
            $configure->save();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $contll->msgOut(false, [], $sqlState, $msg);
        }
    }
}
