<?php

/**
 * @Author: LingPh
 * @Date:   2019-06-25 11:48:22
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-26 20:38:38
 */
namespace App\Http\SingleActions\Frontend\Homepage;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Models\Admin\Notice\FrontendMessageNotice;
use App\Models\DeveloperUsage\Frontend\FrontendAllocatedModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class HompageNoticeAction
{
    protected $model;

    /**
     * @param  FrontendAllocatedModel  $frontendAllocatedModel
     */
    public function __construct(FrontendAllocatedModel $frontendAllocatedModel)
    {
        $this->model = $frontendAllocatedModel;
    }

    /**
     * 首页公告列表
     * @param  FrontendApiMainController  $contll
     * @return JsonResponse
     */
    public function execute(FrontendApiMainController $contll,$input): JsonResponse
    {
        if (Cache::has('homepageNotice')) {
            $data = Cache::get('homepageNotice');
        } else {
            $noticeEloq = $this->model::select('show_num', 'status')->where('en_name', 'notice')->first();
            if ($noticeEloq === null) {
                //#######################################################
                return $contll->msgOut(false, [], '100400');
            }
            if ($noticeEloq->status !== 1) {
                return $contll->msgOut(false, [], '100400');
            }
            $eloqM = new FrontendMessageNotice();
            $contll->inputs['extra_where']['method'] = 'where';
            $contll->inputs['extra_where']['key'] = 'type';
            $contll->inputs['extra_where']['value'] = $input;
            $data = $contll->generateSearchQuery($eloqM, $searchAbleFields = null);
            Cache::forever('homepageNotice', $data);
        }
        return $contll->msgOut(true, $data);
    }
}
