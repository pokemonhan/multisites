<?php

namespace App\Http\Controllers\MobileApi\Game\Lottery;

use App\Http\Controllers\FrontendApi\FrontendApiMainController;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesAvailableIssuesRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesBetRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesIssueHistoryRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesProjectHistoryRequest;
use App\Http\Requests\Frontend\Game\Lottery\LotteriesTracesHistoryRequest;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesAvailableIssuesAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesBetAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesIssueHistoryAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesLotteryInfoAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesLotteryListAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesProjectHistoryAction;
use App\Http\SingleActions\Frontend\Game\Lottery\LotteriesTracesHistoryAction;
use Illuminate\Http\JsonResponse;

class LotteriesController extends FrontendApiMainController
{
    /**
     * 获取彩票列表
     * @param   LotteriesLotteryListAction  $action
     * @return  JsonResponse
     */
    public function lotteryList(LotteriesLotteryListAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 游戏 彩种详情
     * @param   LotteriesLotteryInfoAction  $action
     * @return  JsonResponse
     */
    public function lotteryInfo(LotteriesLotteryInfoAction $action): JsonResponse
    {
        return $action->execute($this);
    }

    /**
     * 历史奖期
     * @param   LotteriesIssueHistoryRequest  $request
     * @param   LotteriesIssueHistoryAction   $action
     * @return  JsonResponse
     * @todo    需要改真实数据 暂时先从那边挪接口
     */
    public function issueHistory(
        LotteriesIssueHistoryRequest $request,
        LotteriesIssueHistoryAction $action
    ): JsonResponse{
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 7. 游戏-可用奖期
     * @param   LotteriesAvailableIssuesRequest  $request
     * @param   LotteriesAvailableIssuesAction   $action
     * @return  JsonResponse
     */
    public function availableIssues(
        LotteriesAvailableIssuesRequest $request,
        LotteriesAvailableIssuesAction $action
    ): JsonResponse{
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 游戏-下注历史
     * @param   LotteriesProjectHistoryRequest  $request
     * @param   LotteriesProjectHistoryAction   $action
     * @return  JsonResponse
     */
    public function projectHistory(
        LotteriesProjectHistoryRequest $request,
        LotteriesProjectHistoryAction $action
    ): JsonResponse{
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 游戏-追号历史
     * @param   LotteriesProjectHistoryRequest  $request
     * @param   LotteriesProjectHistoryAction   $action
     * @return  JsonResponse
     */
    public function tracesHistory(
        LotteriesTracesHistoryRequest $request,
        LotteriesTracesHistoryAction $action
    ): JsonResponse{
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }

    /**
     * 游戏-投注
     * @param   LotteriesBetRequest  $request
     * @param   LotteriesBetAction   $action
     * @return  JsonResponse
     * @throws  Exception
     */
    public function bet(LotteriesBetRequest $request, LotteriesBetAction $action): JsonResponse
    {
        $inputDatas = $request->validated();
        return $action->execute($this, $inputDatas);
    }
}