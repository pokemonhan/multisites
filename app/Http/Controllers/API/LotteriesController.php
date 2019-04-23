<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use App\models\MethodsModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class LotteriesController extends ApiMainController
{
    protected $eloqM = 'LotteriesModel';

    public function seriesLists()
    {
        $seriesData = Config::get('game.main.series');
        return $this->msgout(true,$seriesData);
    }

    public function lotteriesLists()
    {
        $series = array_keys(Config::get('game.main.series'));
        $seriesStringImploded = implode(',', $series);
        $rule = [
            'series_id' => 'required|in:'.$seriesStringImploded,
        ];
        $validator = Validator::make($this->inputs, $rule);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors(), 401);
        }
        $lotteriesEloq = $this->eloqM::where([
            ['series_id', '=', $this->inputs['series_id']],
            ['status', '=', 1],
        ])->get()->toArray();
        return $this->msgout(true,$lotteriesEloq);
    }

    public function lotteriesMethodLists()
    {
        $method =[];
        $redisKey = 'play_method_list';
        if (Cache::has($redisKey)) {
            $method = Cache::get($redisKey);
        } else {
            $seriesList = array_keys(Config::get('game.main.series'));
            foreach ($seriesList as $seriesIthem)
            {
                    $methodEloq = MethodsModel::where([
                        ['series_id', '=', $seriesIthem],
                    ])->first();
                    $lotteriesIds = $methodEloq->lotteriesIds;//        dd($lotteriesIds);
                    foreach ($lotteriesIds as $litems) {
                        $currentLotteryId = $litems->lottery_id;
                        $temp[$seriesIthem][$currentLotteryId] = [];
                        $methodGrops = $litems->methodGroups;
                        foreach ($methodGrops as $mgitems) {
                            $curentMethodGroup = $mgitems->method_group;
                            $temp[$seriesIthem][$currentLotteryId][$curentMethodGroup] = [];
                            $methodRows = $mgitems->methodRows;
                            foreach ($methodRows as $rowitems) {
                                $temp[$seriesIthem][$currentLotteryId][$curentMethodGroup][$rowitems->method_row] = $rowitems->methodDetails->toArray();
                            }
                        }

                    }
                    $method = array_merge($method, $temp);
            }
            $hourToStore = 24;
            $expiresAt = Carbon::now()->addHours($hourToStore)->diffInMinutes();
            Cache::put($redisKey, $method, $expiresAt);
//            Cache::forever($redisKey, $method);
        }
        return $this->msgout(true,$method);
    }
}
