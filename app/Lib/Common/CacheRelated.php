<?php

namespace App\Lib\Common;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CacheRelated
{
    /**
     * @param  string $key
     * @return void
     */
    public function delete($key): void
    {
        if (Cache::has($key)) {
            Cache::forget($key);
        }
    }

    /**
     * @param  $picStr
     * @param  $delimiter
     * @return void
     */
    public static function deleteCachePic($picStr, $delimiter = null): void
    {
        $tags = 'images';
        $redisKey = 'cleaned_images';
        if ($delimiter === null) {
            $picArr = (array) $picStr;
        } else {
            $picArr = explode($delimiter, $picStr);
        }
        if (Cache::tags($tags)->has($redisKey)) {
            $cachePic = Cache::tags($tags)->get($redisKey);
            foreach ($picArr as $picName) {
                if (array_key_exists($picName, $cachePic)) {
                    unset($cachePic[$picName]);
                }
            }
            $hourToStore = 24 * 2;
            $expiresAt = Carbon::now()->addHours($hourToStore);
            Cache::tags($tags)->put($redisKey, $cachePic, $expiresAt);
        }
    }

    public static function getTagsCache($tags, $key)
    {
        $data = false;
        if (Cache::tags($tags)->has($key)) {
            $data = Cache::tags($tags)->get($key);
        }
        return $data;
    }

    /**
     * 设置标签缓存
     * @param   string $tags     [标签]
     * @param   string $key      [缓存键名]
     * @param          $data     [缓存的数据]
     * @param   int    $minute   [缓存的时间（分钟）0永久]
     * @return  void
     */
    public static function setTagsCache($tags, $key, $data, $minute = 0): void
    {
        $minute = (int) $minute;
        if ($minute === 0) {
            Cache::tags($tags)->forever($key, $data);
        } else {
            $expiresAt = Carbon::now()->addMinute($minute);
            Cache::tags($tags)->put($key, $data, $expiresAt);
        }
    }

    /**
     * 删除标签缓存
     * @param  string $tags     [标签]
     * @param  string $key      [缓存键名]
     * @return void
     */
    public static function deleteTagsCache($tags, $key): void
    {
        if (Cache::tags($tags)->has($key)) {
            $data = Cache::tags($tags)->forget($key);
        }
    }
}
