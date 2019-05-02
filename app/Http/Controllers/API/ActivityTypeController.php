<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class ActivityTypeController extends ApiMainController
{
    protected $eloqM = 'ActivityType';
    public function detail()
    {
        $datas = $this->eloqM::where('status', 1)->get()->toArray();
        if (empty($datas)) {
            return $this->msgout(false, [], '没有获取到数据', '0009');
        }
        return $this->msgout(true, $datas);
    }
    /**
     * 编辑活动分类
     */
    public function editActype()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'status' => 'in:0,1',
            'l_size' => 'gt:0',
            'w_size' => 'gt:0',
            'size' => 'numeric|gt:0',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first());
        }
        $editData = $this->eloqM::find($this->inputs['id']);
        if (is_null($editData)) {
            return $this->msgout(false, [], '需要修改的活动分类id不存在');
        }
        //图片类型的分类
        if (array_key_exists('l_size', $this->inputs)) {
            $editData->l_size = $this->inputs['l_size'];
        }
        if (array_key_exists('w_size', $this->inputs)) {
            $editData->w_size = $this->inputs['w_size'];
        }
        if (array_key_exists('status', $this->inputs)) {
            $editData->status = $this->inputs['status'];
        }
        if (array_key_exists('size', $this->inputs)) {
            $editData->size = $this->inputs['size'];
        }
        try {
            $editData->save();
            return $this->msgout(true, [], '修改活动分类成功');
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
}