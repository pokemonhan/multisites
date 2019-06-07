<?php

namespace App\Models\DeveloperUsage\Frontend\Traits;

/**
 * @Author: LingPh
 * @Date:   2019-05-29 17:38:37
 * @Last Modified by:   LingPh
 * @Last Modified time: 2019-06-07 15:28:01
 */
trait FrontendModelTraits
{

    public function allFrontendModel($type)
    {
        if ($type == 2) {
            $typeArr = [1, 2];
        } elseif ($type == 3) {
            $typeArr = [1, 3];
        }
        $parentFrontendModel = self::ParentModel($typeArr);
        $frontendModelList = [];
        foreach ($parentFrontendModel as $id => $frontendModel) {
            $frontendModelList[$id] = $frontendModel;
            $frontendModelList[$id]['childs'] = $frontendModel->childs;
            foreach ($frontendModelList[$id]['childs'] as $grandsonId => $grandsonFrontendModel) {
                $frontendModelList[$id]['childs'][$grandsonId] = $grandsonFrontendModel;
                $frontendModelList[$id]['childs'][$grandsonId]['childs'] = $grandsonFrontendModel->childs;
            }
        }
        return $frontendModelList;
    }

    public function ParentModel($typeArr)
    {
        return self::where('level', 1)->whereIn('type', $typeArr)->get();
    }

    /**
     * 获取一个模块信息
     * @param  string $en_name 模块英文名
     * @return
     */
    public function getModel($en_name)
    {
        return self::where('en_name', $en_name)->first();
    }
}
