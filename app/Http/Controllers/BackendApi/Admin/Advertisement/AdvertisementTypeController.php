<?php

namespace App\Http\Controllers\BackendApi\Admin\Advertisement;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\Validator;

class AdvertisementTypeController extends BackEndApiMainController
{
    protected $eloqM = 'AdvertisementType';

    public function detail()
    {
        $datas = $this->eloqM::get();
        return $this->msgOut(true, $datas);
    }

    /**
     * 编辑广告类型
     */
    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'status' => 'in:0,1',
            'l_size' => 'gt:0',
            'w_size' => 'gt:0',
            'size' => 'numeric|gt:0',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $editData = $this->eloqM::find($this->inputs['id']);
        if (is_null($editData)) {
            return $this->msgOut(false, [], '100400');
        }
        unset($this->inputs['id']);
        $this->editAssignment($editData, $this->inputs);
        try {
            $editData->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}
