<?php

namespace App\Http\Controllers\BackendApi\Admin\Article;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Lib\Common\ImageArrange;
use App\Lib\Common\InternalNoticeMessage;
use App\Models\Admin\BackendAdminUser;
use App\Models\Admin\Message\BackendSystemNoticeList;
use App\Models\BackendAdminAuditFlowList;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Activity\BackendAdminMessageArticle';
    protected $message = '有新的文章需要审核';

    //文章列表
    public function detail(): JsonResponse
    {
        $field = 'sort';
        $type = 'asc';
        $searchAbleFields = ['title', 'type', 'search_text', 'is_for_agent'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields, 0, null, null, $field, $type);
        return $this->msgOut(true, $datas);
    }
    //发布文章
    public function addArticles(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'summary' => 'required|string',
            'content' => 'required|string',
            'search_text' => 'required|string',
            'is_for_agent' => 'required|in:0,1',
            'apply_note' => 'required|string',
            'pic_name' => 'array',
            'pic_path' => 'array',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::where('title', $this->inputs['title'])->first();
        if (!is_null($pastData)) {
            return $this->msgOut(false, [], '100500');
        }
        try {
            //插入 backend_admin_audit_flow_lists 审核表
            $auditFlowId = $this->insertAuditFlow($this->inputs['apply_note']);
            //插入 BackendAdminMessageArticle 文章表
            $addDatas = $this->inputs;
            unset($addDatas['pic_name']);
            $sortdata = $this->eloqM::orderBy('sort', 'desc')->first();
            if (is_null($sortdata)) {
                $sort = 1;
            } else {
                $sort = $sortdata['sort'] + 1;
            }
            $addDatas['sort'] = $sort;
            $addDatas['status'] = 0;
            $addDatas['add_admin_id'] = $this->partnerAdmin['id'];
            $addDatas['last_update_admin_id'] = $this->partnerAdmin['id'];
            if (array_key_exists('pic_path', $this->inputs) && !empty($this->inputs['pic_path'])) {
                $addDatas['pic_path'] = implode('|', $this->inputs['pic_path']);
            }
            $articlesEloq = new $this->eloqM();
            $addDatas['audit_flow_id'] = $auditFlowId;
            $articlesEloq->fill($addDatas);
            $articlesEloq->save();
            //文章发布成功  销毁缓存
            if (array_key_exists('pic_path', $this->inputs)) {
                $this->deleteCachePic($this->inputs['pic_name']);
            }
            //发送站内消息给管理员审核
            $this->sendMessage();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //编辑文章
    public function editArticles(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'title' => 'required|string',
            'summary' => 'required|string',
            'content' => 'required|string',
            'search_text' => 'required|string',
            'is_for_agent' => 'required|in:0,1',
            'apply_note' => 'required|string',
            'pic_name' => 'required|array',
            'pic_path' => 'required|array',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::where('title', $this->inputs['title'])->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($pastData)) {
            return $this->msgOut(false, [], '100500');
        }
        try {
            //插入 backend_admin_audit_flow_lists 审核表
            $auditFlowId = $this->insertAuditFlow($this->inputs['apply_note']);
            $editDataEloq->audit_flow_id = $auditFlowId;
            //
            $editDataEloq = $this->eloqM::find($this->inputs['id']);
            $pastPicPath = $editDataEloq->pic_path;
            $editDatas = $this->inputs;
            unset($editDatas['pic_name']);
            unset($editDatas['pic_name']);
            unset($editDatas['apply_note']);
            $this->editAssignment($editDataEloq, $editDatas);
            $editDataEloq->status = 0;
            $editDataEloq->last_update_admin_id = $this->partnerAdmin['id'];
            //查看是否修改图片
            $new_pic_path = $this->inputs['pic_path'];
            if ($new_pic_path != $pastPicPath) {
                //销毁缓存
                $this->deleteCachePic($this->inputs['pic_name']);
                //删除原图
                $pastPicPathArr = explode('|', $pastPicPath);
                $this->deleteImg($pastPicPathArr);
                //
                $editDataEloq->pic_path = implode('|', $new_pic_path);
            }
            $editDataEloq->save();
            //发送站内消息给管理员审核
            $this->sendMessage();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //删除文章
    public function deleteArticles(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        $picPathArr = explode('|', $pastData['pic_path']);
        if (!is_null($pastData)) {
            DB::beginTransaction();
            try {
                $this->eloqM::where('id', $this->inputs['id'])->delete();
                //排序
                $this->eloqM::where('sort', '>', $pastData['sort'])->decrement('sort');
                //删除图片
                $this->deleteImg($picPathArr);
                DB::commit();
                return $this->msgOut(true);
            } catch (\Exception $e) {
                DB::rollback();
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgOut(false, [], $sqlState, $msg);
            }
        } else {
            return $this->msgOut(false, [], '100501');
        }
    }

    //文章排序
    public function sortArticles(): JsonResponse
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
            return $this->msgOut(false, [], '100501');
        }
        DB::beginTransaction();
        try {
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

    //文章置顶
    public function topArticles(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        $topData = $this->eloqM::find($this->inputs['id']);
        if (is_null($topData)) {
            return $this->msgOut(false, [], '100501');
        }
        try {
            $this->eloqM::where('sort', '<', $topData['sort'])->increment('sort');
            $topData->sort = 1;
            $topData->save();
            return $this->msgOut(true);
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    //图片上传
    public function uploadPic(): JsonResponse
    {
        $validator = Validator::make($this->inputs, [
            'pic' => 'required|file',
        ]);
        if ($validator->fails()) {
            return $this->msgOut(false, [], '400', $validator->errors()->first());
        }
        //接收文件信息
        $ImageClass = new ImageArrange();
        $file = $this->inputs['pic'];
        $folderName = 'articles';
        $depositPath = $ImageClass->depositPath($folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        //进行上传
        $pic = $ImageClass->uploadImg($file, $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '400', $pic['msg']);
        }
        $hourToStore = 24 * 2;
        $expiresAt = Carbon::now()->addHours($hourToStore);
        if (Cache::has('CachePic')) {
            $CachePic = Cache::get('CachePic');
            $CachePic[$pic['name']] = $pic;
        } else {
            $CachePic[$pic['name']] = $pic;
        }
        Cache::put('CachePic', $CachePic, $expiresAt);
        return $this->msgOut(true, $pic);
    }

    /**
     * 删除图片缓存
     * @param  array $picNames 图片名称
     * @return void
     */
    public function deleteCachePic(array $picNames)
    {
        if (Cache::has('CachePic')) {
            $CachePic = Cache::get('CachePic');
            foreach ($picNames as $picName) {
                if (array_key_exists($picName, $CachePic)) {
                    unset($CachePic[$picName]);
                }
            }
            $hourToStore = 24 * 2;
            $expiresAt = Carbon::now()->addHours($hourToStore);
            Cache::put('CachePic', $CachePic, $expiresAt);
        }
    }

    /**
     * insertAuditFlow 插入审核表
     * @param string $apply_note 备注
     * @return int $id
     */
    public function insertAuditFlow($apply_note)
    {
        $flowDatas = [
            'admin_id' => $this->partnerAdmin['id'],
            'apply_note' => $apply_note,
            'admin_name' => $this->partnerAdmin['name'],
        ];
        $flowConfigure = new BackendAdminAuditFlowList;
        $flowConfigure->fill($flowDatas);
        $flowConfigure->save();
        return $flowConfigure->id;
    }

    /**
     * 删除图片
     * @param  array $imgArr
     * @return void
     */
    public function deleteImg(array $imgArr)
    {
        $ImageClass = new ImageArrange();
        foreach ($imgArr as $imgPath) {
            $ImageClass->deletePic($imgPath);
        }
    }

    /**
     * 发送站内消息给管理员审核
     * @return void
     */
    public function sendMessage()
    {
        $messageClass = new InternalNoticeMessage();
        $type = BackendSystemNoticeList::AUDIT;
        $message = $this->message;
        $adminsArr = BackendAdminUser::select('id', 'group_id')->where('group_id', 1)->get()->toArray();
        $messageClass->insertMessage($type, $message, $adminsArr);
    }
}
