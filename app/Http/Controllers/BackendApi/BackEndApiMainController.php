<?php

namespace App\Http\Controllers\BackendApi;

use App\Http\Controllers\Controller;
use App\models\PartnerAdminGroupAccess;
use App\models\PartnerAdminRoute;
use App\models\PartnerMenus;
use App\models\PlatForms;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class BackEndApiMainController extends Controller
{
    protected $inputs;
    protected $partnerAdmin; //当前的商户用户
    protected $currentOptRoute; //目前路由
    protected $fullMenuLists; //所有的菜单
    protected $currentPlatformEloq = null; //当前商户存在的平台
    protected $currentPartnerAccessGroup = null; //当前商户的权限组
    protected $partnerMenulists; //目前所有的菜单为前端展示用的
    protected $eloqM = ''; // 当前的eloquent
    protected $currentRouteName; //当前的route name;
    protected $routeAccessable = false;
    protected $log_uuid; //当前的logId
    protected $currentGuard = 'backend';
    protected $currentAuth;
    protected $guard = 'admin';

    /**
     * AdminMainController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->currentAuth = auth($this->currentGuard);
            $this->partnerAdmin = $this->currentAuth->user();
            if (!is_null($this->partnerAdmin)) {
                //登录注册的时候是没办法获取到当前用户的相关信息所以需要过滤
                $this->currentPartnerAccessGroup = new PartnerAdminGroupAccess();
                $this->currentPlatformEloq = new PlatForms();
                if ($this->partnerAdmin->platform()->exists()) {
                    $this->currentPlatformEloq = $this->partnerAdmin->platform; //获取目前账号用户属于平台的对象
                    if ($this->partnerAdmin->accessGroup()->exists()) {
                        $this->currentPartnerAccessGroup = $this->partnerAdmin->accessGroup;
                    }
                    $this->menuAccess();
                    $this->routeAccessCheck();
                    if ($this->routeAccessable === false) {
                        return $this->msgOut($this->routeAccessable, [], '100001');
                    }
                }
            }
            $this->inputs = Input::all(); //获取所有相关的传参数据
            //登录注册的时候是没办法获取到当前用户的相关信息所以需要过滤
            $this->adminOperateLog();
            $this->eloqM = 'App\\models\\' . $this->eloqM; // 当前的eloquent
            return $next($request);
        });
    }

    /**
     *　初始化所有菜单，目前商户该拥有的菜单与权限
     */
    private function menuAccess()
    {
        $partnerEloq = new PartnerMenus();
        $this->fullMenuLists = $partnerEloq->forStar(); //所有的菜单
        $this->partnerMenulists = $partnerEloq->menuLists($this->currentPartnerAccessGroup); //目前所有的菜单为前端展示用的
    }

    /**
     *　检测目前的路由是否有权限访问
     */
    private function routeAccessCheck(): void
    {
        $this->currentOptRoute = Route::getCurrentRoute();
        $this->currentRouteName = $this->currentOptRoute->action['as']; //当前的route name;
        //$partnerAdREloq = PartnerAdminRoute::where('route_name',$this->currentRouteName)->first()->parentRoute->menu;
        $partnerAdREloq = PartnerAdminRoute::where('route_name', $this->currentRouteName)->first();
        if (!is_null($partnerAdREloq)) {
            $partnerMenuEloq = $partnerAdREloq->menu;
            //set if it is accissable or not
            if (!empty($this->currentPartnerAccessGroup->role)) {
                if ($this->currentPartnerAccessGroup->role == '*') {
                    $this->routeAccessable = true;
                } else {
                    $currentRouteGroup = json_decode($this->currentPartnerAccessGroup->role, true);
                    if (in_array($partnerMenuEloq->id, $currentRouteGroup)) {
                        $this->routeAccessable = true;
                    } elseif (in_array($this->currentRouteName, Config::get('routelistexclude'))) {
                        $this->routeAccessable = true;
                    }
                }
            }
        }
    }

    /**
     *记录后台管理员操作日志
     */
    private function adminOperateLog(): void
    {
        $this->log_uuid = Str::orderedUuid()->getNodeHex();
        $datas['input'] = $this->inputs;
        $datas['route'] = $this->currentOptRoute;
        $datas['log_uuid'] = $this->log_uuid;
        $log = json_encode($datas, JSON_UNESCAPED_UNICODE);
        Log::channel('apibyqueue')->info($log);
    }

    /**
     * @param  bool  $success
     * @param  array  $data
     * @param  string  $message
     * @param  string  $code
     * @return JsonResponse
     */
    protected function msgOut($success = false, $data = [], $code = '', $message = ''): JsonResponse
    {
        /*if ($this->currentAuth->user())
        {
        $data['access_token']=$this->currentAuth->user()->remember_token;
        }*/
        $defaultSuccessCode = 200;
        $defaultErrorCode = 404;
        if ($success === true) {
            $code = $code == '' ? $defaultSuccessCode : $code;
        } else {
            $code = $code == '' ? $defaultErrorCode : $code;
        }
        $message = $message == '' ? __('codes-map.' . $code) : $message;
        $datas = [
            'success' => $success,
            'code' => $code,
            'data' => $data,
            'message' => $message,
        ];
        return response()->json($datas);
    }

    protected function modelWithNameSpace($eloqM = null)
    {
        return !is_null($eloqM) ? 'App\\models\\' . $eloqM : $eloqM;
    }

    /**
     * Generate Search Query
     * @param $eloqM
     * @param $searchAbleFields
     * @param  int  $fixedJoin
     * @param $withTable
     * @param $withSearchAbleFields
     * @param  string  $orderFields
     * @param  string  $orderFlow
     * @return mixed
     */
    public function generateSearchQuery(
        $eloqM,
        $searchAbleFields,
        $fixedJoin = 0,
        $withTable = null,
        $withSearchAbleFields = null,
        $orderFields = 'updated_at',
        $orderFlow = 'desc'
    ) {
        $searchCriterias = Arr::only($this->inputs, $searchAbleFields);
        $queryConditionField = $this->inputs['query_conditions'] ?? '';
        $queryConditions = Arr::wrap(json_decode($queryConditionField, true));
        $timeConditionField = $this->inputs['time_condtions'] ?? '';
        $timeConditions = Arr::wrap(json_decode($timeConditionField, true));
        $extraWhereContitions = $this->inputs['extra_where'] ?? [];
        $extraContitions = $this->inputs['extra_column'] ?? [];
        $queryEloq = new $eloqM;
        $sizeOfInputs = sizeof($searchCriterias);
        //with Criterias
        $withSearchCriterias = Arr::only($this->inputs, $withSearchAbleFields);
        $sizeOfWithInputs = sizeof($withSearchCriterias);

        $pageSize = $this->inputs['page_size'] ?? 20;
        if ($sizeOfInputs == 1) {
            //for single where condition searching
            if (!empty($searchCriterias)) {
                foreach ($searchCriterias as $key => $value) {
                    $sign = array_key_exists($key, $queryConditions) ? $queryConditions[$key] : '=';
                    if ($sign == 'LIKE') {
                        $sign = strtolower($sign);
                        $value = '%' . $value . '%';
                    }
                    $whereCriteria = [];
                    $whereCriteria[] = $key;
                    $whereCriteria[] = $sign;
                    $whereCriteria[] = $value;
                    $whereData[] = $whereCriteria;
                }
                if (!empty($timeConditions)) {
                    $whereData = array_merge($whereData, $timeConditions);
                }
                if (!empty($extraContitions)) {
                    $whereData = array_merge($whereData, $extraContitions);
                }
                $queryEloq = $eloqM::where($whereData);
                if ($fixedJoin > 0) {
                    $queryEloq = $this->eloqToJoin($queryEloq, $fixedJoin, $withTable, $sizeOfWithInputs,
                        $withSearchCriterias, $queryConditions);
                }
            } else {
                //for default
                if ($fixedJoin > 0) {
                    $queryEloq = $this->eloqToJoin($queryEloq, $fixedJoin, $withTable, $sizeOfWithInputs,
                        $withSearchCriterias, $queryConditions);
                }
            }
        } else {
            if ($sizeOfInputs > 1) {
                //for multiple where condition searching
                if (!empty($searchCriterias)) {
                    $whereData = [];
                    foreach ($searchCriterias as $key => $value) {
                        $sign = array_key_exists($key, $queryConditions) ? $queryConditions[$key] : '=';
                        if ($sign == 'LIKE') {
                            $sign = strtolower($sign);
                            $value = '%' . $value . '%';
                        }
                        $whereCriteria = [];
                        $whereCriteria[] = $key;

                        $whereCriteria[] = $sign;
                        $whereCriteria[] = $value;
                        $whereData[] = $whereCriteria;
                    }
                    if (!empty($timeConditions)) {
                        $whereData = array_merge($whereData, $timeConditions);
                    }
                    if (!empty($extraContitions)) {
                        $whereData = array_merge($whereData, $extraContitions);
                    }
                    $queryEloq = $eloqM::where($whereData);
                    if ($fixedJoin > 0) {
                        $queryEloq = $this->eloqToJoin($queryEloq, $fixedJoin, $withTable, $sizeOfWithInputs,
                            $withSearchCriterias, $queryConditions);
                    }
                } else {
                    if ($fixedJoin > 0) {
                        $queryEloq = $this->eloqToJoin($queryEloq, $fixedJoin, $withTable, $sizeOfWithInputs,
                            $withSearchCriterias, $queryConditions);
                    }
                }
            } else {
                $whereData = [];
                if (!empty($timeConditions)) {
                    $whereData = $timeConditions;
                }
                if (!empty($extraContitions)) {
                    $whereData = array_merge($whereData, $extraContitions);
                }
                if (!empty($whereData)) {
                    $queryEloq = $eloqM::where($whereData); //$extraContitions
                }
                if ($fixedJoin > 0) {
                    $queryEloq = $this->eloqToJoin($queryEloq, $fixedJoin, $withTable, $sizeOfWithInputs,
                        $withSearchCriterias, $queryConditions);
                }
            }
        }
        //extra wherein condition
        if (!empty($extraWhereContitions)) {
            $method = $extraWhereContitions['method'];
            $queryEloq = $queryEloq->$method($extraWhereContitions['key'], $extraWhereContitions['value']);
        }
        $data = $queryEloq->orderBy($orderFields, $orderFlow)->paginate($pageSize);
        return $data;
    }

    /**
     * Join Table with Eloquent
     * @param $queryEloq
     * @param $fixedJoin
     * @param $withTable
     * @param $sizeOfWithInputs
     * @param $withSearchCriterias
     * @param $queryConditions
     * @return mixed
     */
    public function eloqToJoin(
        $queryEloq,
        $fixedJoin,
        $withTable,
        $sizeOfWithInputs,
        $withSearchCriterias,
        $queryConditions
    ) {
        if (empty($sizeOfWithInputs)) //如果with 没有参数可以查询时查询全部
        {
            switch ($fixedJoin) {
                case 1: //有一个连表查询的情况下
                    $queryEloq = $queryEloq->with($withTable);
                    break;
            }
        } else {
            switch ($fixedJoin) {
                case 1: //有一个连表查询的情况下
                    $queryEloq = $queryEloq->with($withTable)->whereHas($withTable,
                        function ($query) use ($sizeOfWithInputs, $withSearchCriterias, $queryConditions) {
                            if ($sizeOfWithInputs > 1) {

                                if (!empty($withSearchCriterias)) {
                                    foreach ($withSearchCriterias as $key => $value) {
                                        $whereCriteria = [];
                                        $whereCriteria[] = $key;
                                        $whereCriteria[] = array_key_exists($key,
                                            $queryConditions) ? $queryConditions[$key] : '=';
                                        $whereCriteria[] = $value;
                                        $whereData[] = $whereCriteria;
                                    }
                                    $query->where($whereData);
                                }
                            } else {
                                if ($sizeOfWithInputs == 1) {
                                    if (!empty($withSearchCriterias)) {
                                        foreach ($withSearchCriterias as $key => $value) {
                                            $sign = array_key_exists($key,
                                                $queryConditions) ? $queryConditions[$key] : '=';
                                            if ($sign == 'LIKE') {
                                                $sign = strtolower($sign);
                                                $value = '%' . $value . '%';
                                            }
                                            $query->where($key, $sign, $value);
                                        }
                                    }
                                }
                            }
                        });
                    break;
            }
        }

        return $queryEloq;
    }

    /**
     * @param $eloqM
     * @param  array  $datas
     */
    public function editAssignment($eloqM, $datas)
    {
        foreach ($datas as $k => $v) {
            $eloqM->$k = $v;
        }
        return $eloqM;
    }

}