<?php

namespace App\Models;

use App\Models\Logics\TraceTraits;

class LotteryTrace extends BaseModel
{
    use TraceTraits;
    protected $table = 'lottery_traces';

}