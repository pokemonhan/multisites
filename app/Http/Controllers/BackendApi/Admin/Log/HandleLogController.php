<?php

namespace App\Http\Controllers\BackendApi\Admin\Log;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Log\HandleLogGetAddressRequest;
use App\Lib\Common\IpAddress;
use App\Models\Admin\FrontendSystemLog;
use App\Models\Admin\SystemAddressIp;
use Illuminate\Http\JsonResponse;

class HandleLogController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\BackendSystemLog';

    /**
     * 后台日志列表
     * @return JsonResponse
     */
    public function details(): JsonResponse
    {
        $searchAbleFields = ['origin', 'ip', 'device', 'os', 'os_version', 'browser', 'admin_name', 'menu_label', 'device_type'];
        $data = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        return $this->msgOut(true, $data);
    }

    /**
     * 前台日志列表
     * @return JsonResponse
     */
    public function frontendLogs(): JsonResponse
    {
        $logEloq = new FrontendSystemLog();
        $searchAbleFields = ['origin', 'ip', 'device', 'os', 'os_version', 'browser', 'username', 'menu_label', 'device_type'];
        $data = $this->generateSearchQuery($logEloq, $searchAbleFields);
        return $this->msgOut(true, $data);
    }

    /**
     * IP获取地址
     * @param  HandleLogGetAddressRequest $request
     * @return JsonResponse
     */
    public function getAddress(HandleLogGetAddressRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $addressIpELoq = SystemAddressIp::where('ip', $inputDatas['ip'])->first();
        if (is_null($addressIpELoq)) {
            $ipAddressCla = new IpAddress();
            $addressIpELoq = $ipAddressCla->getAddress($inputDatas['ip']);
        }
        $data = [
            'ip' => $addressIpELoq->ip,
            'country' => $addressIpELoq->country,
            'region' => $addressIpELoq->region,
            'city' => $addressIpELoq->city,
            'county' => $addressIpELoq->county,
        ];
        return $this->msgOut(true, $data);
    }
}
