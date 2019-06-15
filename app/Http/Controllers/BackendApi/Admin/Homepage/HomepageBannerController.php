<?php

namespace App\Http\Controllers\BackendApi\Admin\Homepage;

use App\Http\Controllers\BackendApi\BackEndApiMainController;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerAddRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerDeleteRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerEditRequest;
use App\Http\Requests\Backend\Admin\Homepage\HomepageBannerSortRequest;
use App\Lib\Common\ImageArrange;
use App\Models\Admin\Activity\FrontendActivityContent;
use App\Models\Advertisement\FrontendSystemAdsType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomepageBannerController extends BackEndApiMainController
{
    protected $eloqM = 'Admin\Homepage\FrontendPageBanner';
    protected $folderName = 'Homepagec_Rotation_chart';

    /**
     * 首页轮播图列表
     * @return JsonResponse
     */
    public function detail(): JsonResponse
    {
        $data = $this->eloqM::orderBy('sort', 'asc')->get()->toArray();
        return $this->msgOut(true, $data);
    }

    /**
     * 添加首页轮播图
     * @param HomepageBannerAddRequest $request
     * @return JsonResponse
     */
    public function add(HomepageBannerAddRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        //上传图片
        $imageObj = new ImageArrange();
        $folderName = $this->folderName;
        $depositPath = $imageObj->depositPath($folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $pic = $imageObj->uploadImg($inputDatas['pic'], $depositPath);
        if ($pic['success'] === false) {
            return $this->msgOut(false, [], '400', $pic['msg']);
        }
        //生成缩略图
        $thumbnail = $imageObj->creatThumbnail($pic['path'], 100, 200);
        $addData = $inputDatas;
        unset($addData['pic']);
        $addData['pic_path'] = '/' . $pic['path'];
        $addData['thumbnail_path'] = '/' . $thumbnail;
        //sort
        $maxSort = $this->eloqM::select('sort')->max('sort');
        $addData['sort'] = ++$maxSort;
        try {
            $rotationChartEloq = new $this->eloqM;
            $rotationChartEloq->fill($addData);
            $rotationChartEloq->save();
            //清除首页banner缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 编辑首页轮播图
     * @param  HomepageBannerEditRequest $request
     * @return JsonResponse
     */
    public function edit(HomepageBannerEditRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastData = $this->eloqM::find($inputDatas['id']);
        $checkTitle = $this->eloqM::where('title', $inputDatas['title'])->where('id', '!=', $inputDatas['id'])->exists();
        if ($checkTitle !== null) {
            return $this->msgOut(false, [], '101800');
        }
        $editData = $inputDatas;
        unset($editData['pic']);
        //如果要修改图片  删除原图  上传新图
        if (isset($inputDatas['pic'])) {
            $imageObj = new ImageArrange();
            $picData = $this->replaceImage($pastData['pic_path'], $pastData['thumbnail_path'], $inputDatas['pic'], $imageObj);
            if ($picData['success'] === false) {
                return $this->msgOut(false, [], $picData['code']);
            }
            //上传缩略图
            $thumbnail = $imageObj->creatThumbnail($picData['path'], 100, 200);
            $editData['pic_path'] = '/' . $picData['path'];
            $editData['thumbnail_path'] = '/' . $thumbnail;
        }
        try {
            $this->editAssignment($pastData, $editData);
            $pastData->save();
            //清除首页banner缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 删除首页轮播图
     * @param  HomepageBannerDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(HomepageBannerDeleteRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        $pastDataEloq = $this->eloqM::find($inputDatas['id']);
        $pastData = $pastDataEloq;
        DB::beginTransaction();
        try {
            $imageObj = new ImageArrange();
            $pastDataEloq->delete();
            //往后的sort重新排序
            $this->eloqM::where('sort', '>', $pastData->sort)->decrement('sort');
            DB::commit();
            $deleteStatus = $imageObj->deletePic(substr($pastData->pic_path, 1));
            //清除首页banner缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 首页轮播图排序
     * @param  HomepageBannerSortRequest $request
     * @return JsonResponse
     */
    public function sort(HomepageBannerSortRequest $request): JsonResponse
    {
        $inputDatas = $request->validated();
        DB::beginTransaction();
        try {
            //上拉排序
            if ($inputDatas['sort_type'] == 1) {
                $stationaryData = $this->eloqM::find($inputDatas['front_id']);
                $stationaryData->sort = $inputDatas['front_sort'];
                $this->eloqM::where('sort', '>=', $inputDatas['front_sort'])->where('sort', '<', $inputDatas['rearways_sort'])->increment('sort');
                //下拉排序
            } elseif ($inputDatas['sort_type'] == 2) {
                $stationaryData = $this->eloqM::find($inputDatas['rearways_id']);
                $stationaryData->sort = $inputDatas['rearways_sort'];
                $this->eloqM::where('sort', '>', $inputDatas['front_sort'])->where('sort', '<=', $inputDatas['rearways_sort'])->decrement('sort');
            }
            $stationaryData->save();
            DB::commit();
            //清除首页banner缓存
            $this->deleteCache();
            return $this->msgOut(true);
        } catch (Exception $e) {
            DB::rollback();
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgOut(false, [], $sqlState, $msg);
        }
    }

    /**
     * 操作轮播图时获取的活动列表
     * @return  JsonResponse
     */
    public function activityList(): JsonResponse
    {
        $activityList = FrontendActivityContent::select('id', 'title')->where('status', 1)->get()->toArray();
        return $this->msgOut(true, $activityList);
    }

    /**
     * 修改轮播图时   替换图片
     * @param     $pastImg      原图路径
     * @param     $thumbnail    缩略图路径
     * @param     $newImg       新图文件
     * @param     $imageObj     处理图片对象
     * @return    array
     */
    public function replaceImage($pastImg, $thumbnail, $newImg, $imageObj): array
    {
        $imageObj->deletePic(substr($pastImg, 1));
        $imageObj->deletePic(substr($thumbnail, 1));
        $folderName = $this->folderName;
        $depositPath = $imageObj->depositPath($folderName, $this->currentPlatformEloq->platform_id, $this->currentPlatformEloq->platform_name);
        $picData = $imageObj->uploadImg($newImg, $depositPath);
        if ($picData['success'] === true) {
            return $picData;
        } else {
            return ['success' => false, 'code' => '101801'];
        }
    }

    /**
     * 上传图片的规格
     * @return JsonResponse
     */
    public function picStandard(): JsonResponse
    {
        $standard = FrontendSystemAdsType::select('l_size', 'w_size', 'size')->where('type', 1)->first()->toArray();
        return $this->msgOut(true, $standard);
    }

    /**
     * 清除首页banner缓存
     * @return void
     */
    public function deleteCache(): void
    {
        if (Cache::has('homepageBanner')) {
            Cache::forget('homepageBanner');
        }
    }
}
