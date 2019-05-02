<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class IssueSeparateGenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $day;
    protected $rules;
    protected $lotteryEloq;

    /**
     * Create a new job instance.
     *
     * @param $day
     * @param $rules
     * @param $lotteryEloq
     */
    public function __construct($day, $rules, $lotteryEloq)
    {
        $this->day = $day;
        $this->rules = $rules;
        $this->lotteryEloq = $lotteryEloq;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $res = $this->lotteryEloq->_genIssue($this->day, $this->rules);

        if($res!== true)
        {
            $message = $res . '奖期数据无法生成' . json_encode($this->lotteryEloq->toArray(), JSON_UNESCAPED_UNICODE) . '[' . $this->day . ']'."\n";
            Log::channel('issues')->error($message);
        }
    }
}