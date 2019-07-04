<?php

namespace App\Http\SingleActions\Backend\Game\Lottery;

use App\Http\Controllers\backendApi\BackEndApiMainController;
use App\Jobs\Lottery\Encode\IssueEncoder;
use App\Models\Game\Lottery\LotteryIssue;
use Illuminate\Http\JsonResponse;

class LotteriesCalculateEncodeAgainAction
{
    /**
     * 奖期重新派奖
     * @param   BackEndApiMainController  $contll
     * @param   $inputDatas
     * @return  JsonResponse
     */
    public function execute(BackEndApiMainController $contll, $inputDatas): JsonResponse
    {
        $lotteryIssueEloq = LotteryIssue::find($inputDatas['id']);
        dispatch(new IssueEncoder($lotteryIssueEloq->toArray()))->onQueue('issues');
        return $contll->msgOut(true);
    }
}