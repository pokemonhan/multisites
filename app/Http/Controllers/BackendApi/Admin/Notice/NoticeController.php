<?php

namespace App\Http\Controllers\BackendApi\Admin\Notice;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NoticeController extends BackEndApiMainController
{
    protected $eloqM = 'Notice';

    public function detail()
    {
        $noticeDatas = $this->eloqM::select('id', 'type', 'title', 'content', 'start_time', 'end_time', 'sort', 'status', 'admin_id')->with('admin')->orderBy('sort', 'asc')->get()->toArray();
        foreach ($noticeDatas as $key => $data) {
            $noticeDatas[$key]['admin_name'] = $data['admin']['name'];
            unset($noticeDatas[$key]['admin_id']);
            unset($noticeDatas[$key]['admin']);
        }
        return $this->msgOut(true, $noticeDatas);
    }

    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'type' => 'required|numeric',
            'title' => 'required|string',
            'content' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'status' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $checkTitle = $this->eloqM::where('title', $this->inputs['title'])->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '102100');
        }
        $addData = $this->inputs;
        //admin_id
        $addData['admin_id'] = $this->partnerAdmin->id;
        //sort
        $sortdata = $this->eloqM::orderBy('sort', 'desc')->first();
        if (is_null($sortdata)) {
            $addData['sort'] = 1;
        } else {
            $addData['sort'] = $sortdata['sort'] + 1;
        }
        try {
            $noticeEloq = new $this->eloqM;
            $noticeEloq->fill($addData);
            $noticeEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'type' => 'required|numeric',
            'title' => 'required|string',
            'content' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'status' => 'required|numeric|in:0,1',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastEloq)) {
            return $this->msgOut(false, [], '102101');
        }
        $checkTitle = $this->eloqM::where('title', $this->inputs['title'])->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($checkTitle)) {
            return $this->msgOut(false, [], '102100');
        }
        try {
            $this->editAssignment($pastEloq, $this->inputs);
            $pastEloq->save();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($pastEloq)) {
            return $this->msgOut(false, [], '102101');
        }
        //sort
        $sort = $pastEloq->sort;
        DB::beginTransaction();
        try {
            $pastEloq->delete();
            $this->eloqM::where('sort', '>', $sort)->decrement('sort');
            DB::commit();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    public function sort()
    {
        $validator = Validator::make($this->inputs, [
            'front_id' => 'required|numeric|gt:0',
            'rearways_id' => 'required|numeric|gt:0',
            'front_sort' => 'required|numeric|gt:0',
            'rearways_sort' => 'required|numeric|gt:0',
            'sort_type' => 'required|numeric|in:1,2',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastFrontData = $this->eloqM::find($this->inputs['front_id']);
        $pastRearwaysData = $this->eloqM::find($this->inputs['rearways_id']);
        if (is_null($pastFrontData) || is_null($pastRearwaysData)) {
            return $this->msgOut(false, [], '102102');
        }
        DB::beginTransaction();
        try {
            //lockForUpdate
            $this->eloqM::where(function ($query) {
                $query->where('sort', '>=', $this->inputs['front_sort'])
                    ->where('sort', '<=', $this->inputs['rearways_sort']);
            })->lockForUpdate();
            //上拉排序
            if ($this->inputs['sort_type'] == 1) {
                $stationaryData = $pastFrontData;
                $stationaryData->sort = $this->inputs['front_sort'];
                $this->eloqM::where('sort', '>=', $this->inputs['front_sort'])->where('sort', '<', $this->inputs['rearways_sort'])->increment('sort');
                //下拉排序
            } elseif ($this->inputs['sort_type'] == 2) {
                $stationaryData = $pastRearwaysData;
                $stationaryData->sort = $this->inputs['rearways_sort'];
                $this->eloqM::where('sort', '>', $this->inputs['front_sort'])->where('sort', '<=', $this->inputs['rearways_sort'])->decrement('sort');
            }
            $stationaryData->save();
            DB::commit();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }
}