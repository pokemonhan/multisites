<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-24 15:11:20
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-24 15:15:44
 */
namespace App\Http\SingleActions\Backend\DeveloperUsage\Frontend;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Models\DeveloperUsage\Frontend\FrontendWebRoute;
use Exception;
use Illuminate\Http\JsonResponse;

class FrontendWebRouteAddAction
{
    protected $model;

    /**
     * @param  FrontendWebRoute  $frontendWebRoute
     */
    public function __construct(FrontendWebRoute $frontendWebRoute)
    {
        $this->model = $frontendWebRoute;
    }

    /**
     * 添加web路由
     * @param   BackEndApiMainController  $contll
     * @param   $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        try {
            $routeEloq = new $this->model;
            $routeEloq->fill($inputDatas);
            $routeEloq->save();
            return $contll->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $contll->msgOut(false, [], $sqlState, $msg);
        }
    }
}