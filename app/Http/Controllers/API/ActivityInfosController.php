<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiMainController;
use Illuminate\Support\Facades\Validator;

class ActivityInfosController extends ApiMainController
{
    protected $eloqM = 'ActivityInfos';
    //活动列表
    public function detail()
    {
        $searchAbleFields = ['title', 'type', 'status', 'admin_name', 'is_time_interval'];
        $datas = $this->generateSearchQuery($this->eloqM, $searchAbleFields);
        if (empty($datas)) {
            return $this->msgout(false, [], '没有获取到数据', '0009');
        }
        return $this->msgout(true, $datas);
    }
    //添加活动
    public function add()
    {
        $validator = Validator::make($this->inputs, [
            'title' => 'required',
            'type' => 'required|numeric',
            'content' => 'required',
            'pic' => 'required|image|mimes:jpeg,png,jpg',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s',
            'status' => 'required',
            'redirect_url' => 'required',
            'is_time_interval' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first());
        }
        $pastData = $this->eloqM::where('title', $this->inputs['title'])->first();
        if (!is_null($pastData)) {
            return $this->msgout(false, [], '该活动名已存在', '0009');
        }
        //接收文件信息
        $file = $this->inputs['pic'];
        $path = 'uploaded_files/' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id . '/mobile_activity_' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id;
        //进行上传
        $pic = $this->uploadImg($file, $path);
        if ($pic['success'] === false) {
            return $this->msgout(false, [], $pic['message'], '0009');
        }
        //生成缩略图
        $thumbnail_path = $this->creatThumbnail($pic['path'], 100, 200, 'sm_');
        $addDatas = [
            'title' => $this->inputs['title'],
            'type' => $this->inputs['type'],
            'content' => $this->inputs['content'],
            'pic_path' => '/' . $pic['path'],
            'thumbnail_path' => '/' . $thumbnail_path,
            'start_time' => $this->inputs['start_time'],
            'end_time' => $this->inputs['end_time'],
            'status' => $this->inputs['status'],
            'admin_id' => $this->partnerAdmin['id'],
            'admin_name' => $this->partnerAdmin['name'],
            'redirect_url' => $this->inputs['redirect_url'],
            'is_time_interval' => $this->inputs['is_time_interval'],
        ];
        try {
            $configure = new $this->eloqM();
            $configure->fill($addDatas);
            $configure->save();
            return $this->msgout(true, [], '添加活动成功');
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误妈，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
    //编辑活动
    public function edit()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
            'title' => 'required',
            'type' => 'required|numeric',
            'content' => 'required',
            'pic' => 'image|mimes:jpeg,png,jpg',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s',
            'status' => 'required',
            'redirect_url' => 'required',
            'is_time_interval' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first());
        }
        $pastData = $this->eloqM::where('title', $this->inputs['title'])->where('id', '!=', $this->inputs['id'])->first();
        if (!is_null($pastData)) {
            return $this->msgout(false, [], '该活动名已存在', '0009');
        }
        $editDataEloq = $this->eloqM::find($this->inputs['id']);
        if (is_null($editDataEloq)) {
            return $this->msgout(false, [], '该活动id不存在', '0009');
        }
        $editDataEloq->title = $this->inputs['title'];
        $editDataEloq->type = $this->inputs['type'];
        $editDataEloq->content = $this->inputs['content'];
        $editDataEloq->start_time = $this->inputs['start_time'];
        $editDataEloq->end_time = $this->inputs['end_time'];
        $editDataEloq->status = $this->inputs['status'];
        $editDataEloq->redirect_url = $this->inputs['redirect_url'];
        $editDataEloq->is_time_interval = $this->inputs['is_time_interval'];
        //如果修改了图片
        if (isset($this->inputs['pic']) && !is_null($this->inputs['pic'])) {
            $pastpic = $editDataEloq->pic_path;
            $thumbnail_path = $editDataEloq->thumbnail_path;
            //接收文件信息
            $file = $this->inputs['pic'];
            $path = 'uploaded_files/' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id . '/mobile_activity_' . $this->currentPlatformEloq->platform_name . '_' . $this->currentPlatformEloq->platform_id;
            //进行上传
            $pic = $this->uploadImg($file, $path);
            if ($pic['success'] === false) {
                return $this->msgout(false, [], $pic['message'], '0009');
            }
            $editDataEloq->pic_path = '/' . $pic['path'];
            //生成缩略图
            $editDataEloq->thumbnail_path = '/' . $this->creatThumbnail($pic['path'], 100, 200, 'sm_');
        }
        try {
            $editDataEloq->save();
            if (isset($this->inputs['pic']) && !is_null($this->inputs['pic'])) {
                //删除原图片
                $this->deletePic(substr($pastpic, 1));
                $this->deletePic(substr($thumbnail_path, 1));
            }
            return $this->msgout(true, [], '修改活动成功');
        } catch (\Exception $e) {
            $errorObj = $e->getPrevious()->getPrevious();
            [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
            return $this->msgout(false, [], $msg, $sqlState);
        }
    }
    //删除活动
    public function delete()
    {
        $validator = Validator::make($this->inputs, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->msgout(false, [], $validator->errors()->first(), 200);
        }
        $pastData = $this->eloqM::find($this->inputs['id']);
        if (!is_null($pastData)) {
            try {
                $this->eloqM::where('id', $this->inputs['id'])->delete();
                //删除图片
                $this->deletePic(substr($pastData['pic_path'], 1));
                $this->deletePic(substr($pastData['thumbnail_path'], 1));
                return $this->msgout(true, [], '删除活动成功');
            } catch (\Exception $e) {
                $errorObj = $e->getPrevious()->getPrevious();
                [$sqlState, $errorCode, $msg] = $errorObj->errorInfo; //［sql编码,错误码，错误信息］
                return $this->msgout(false, [], $msg, $sqlState);
            }
        } else {
            return $this->msgout(false, [], '该活动不存在', '0009');
        }
    }
    //图片上传
    public function uploadImg($file, $url_path)
    {
        // 检验一下上传的文件是否有效.
        if ($file->isValid()) {
            // 缓存在tmp文件夹中的文件名 例如 php8933.tmp 这种类型的.
            $clientName = $file->getClientOriginalName();
            // 上传文件的后缀.
            $entension = $file->getClientOriginalExtension();
            $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $entension;
            if (!file_exists($url_path)) {
                mkdir($url_path, 0777, true);
            }
            if (!is_writable(dirname($url_path))) {
                return ['success' => false, 'message' => dirname($url_path) . ' 请设置权限!!!'];
            } else {
                $file->move($url_path, $newName);
            }
            // 这里public_path()就是public文件夹所在的路径.$newName 通过算法获得的文件的名称.主要是不能重复产生冲突即可.
            // 利用日期和客户端文件名结合 使用md5 算法加密得到结果.后面加上文件原始的拓展名.
            //文件名
            $namePath = $url_path . '/' . $newName;
            return ['success' => true, 'name' => $newName, 'path' => $namePath];
        }
    }
    public function deletePic($path)
    {
        if (file_exists($path)) {
            if (!is_writable(dirname($path))) {
                return $this->msgout(true, [], dirname($path) . ' 请设置权限!!!');
            } else {
                return unlink($path);
            }
        }
    }
    /**
     *
     * 制作缩略图
     * @param $src_path string 原图路径
     * @param $max_w int 画布的宽度
     * @param $max_h int 画布的高度
     * @param $flag bool 是否是等比缩略图  默认为true
     * @param $prefix string 缩略图的前缀  默认为'sm_'
     *
     */
    public function creatThumbnail($src_path, $max_w, $max_h, $prefix = 'sm_', $flag = true)
    {
        //获取文件的后缀
        $ext = strtolower(strrchr($src_path, '.'));
        //判断文件格式
        switch ($ext) {
            case '.jpg':
                $type = 'jpeg';
                break;
            case '.jpeg':
                $type = 'jpeg';
                break;
            case '.png':
                $type = 'png';
                break;
        }
        //拼接打开图片的函数
        $open_fn = 'imagecreatefrom' . $type;
        //打开源图
        $src = $open_fn($src_path);
        //创建目标图
        $dst = imagecreatetruecolor($max_w, $max_h);
        //源图的宽
        $src_w = imagesx($src);
        //源图的高
        $src_h = imagesy($src);
        //是否等比缩放
        if ($flag) {
            //等比
            //求目标图片的宽高
            if ($max_w / $max_h < $src_w / $src_h) {
                //横屏图片以宽为标准
                $dst_w = $max_w;
                $dst_h = $max_w * $src_h / $src_w;
            } else {
                //竖屏图片以高为标准
                $dst_h = $max_h;
                $dst_w = $max_h * $src_w / $src_h;
            }
            //在目标图上显示的位置
            $dst_x = (int) (($max_w - $dst_w) / 2);
            $dst_y = (int) (($max_h - $dst_h) / 2);
        } else {
            //不等比
            $dst_x = 0;
            $dst_y = 0;
            $dst_w = $max_w;
            $dst_h = $max_h;
        }
        //生成缩略图
        $fool = imagecopyresampled($dst, $src, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        //文件名
        $filename = basename($src_path);
        //文件夹名
        $foldername = substr(dirname($src_path), 0);
        //缩略图存放路径
        $thumb_path = $foldername . '/' . $prefix . $filename;
        //把缩略图上传到指定的文件夹
        imagepng($dst, $thumb_path);
        //销毁图片资源
        imagedestroy($dst);
        imagedestroy($src);
        //返回新的缩略图的文件名
        return $thumb_path;
    }
}