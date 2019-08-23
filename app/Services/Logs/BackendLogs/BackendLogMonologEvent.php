<?php
/**
 * Created by PhpStorm.
 * author: harris
 * Date: 3/27/19
 * Time: 10:37 AM
 */

namespace App\Services\Logs\BackendLogs;

use Illuminate\Queue\SerializesModels;

class BackendLogMonologEvent
{
    use SerializesModels;
    /**
     * @var
     */
    public $records;

    /**
     * @param array $records
     */
    public function __construct(array $records)
    {
        $this->records = $records;
    }
}
