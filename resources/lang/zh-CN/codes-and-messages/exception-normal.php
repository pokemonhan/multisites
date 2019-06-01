<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/7/2019
 * Time: 8:23 PM
 */

return [
    //AuthController
    '100001' => '您没有访问权限',
    '100002' => '账号密码错误',
    '100003' => '旧密码不匹配',
    '100004' => '没有此用户',
    '100005' => '你已多次登录展示不能登录',
    //UserHandleController
    '100100' => '更改密码已有申请',
    '100101' => '更改资金密码已有申请',
    '100102' => '没有此条信息',
    '100103' => '请先添加 sign=artificial_deduction的人工扣款 帐变类型',
    '100104' => '用户剩余金额少于需要扣除的金额',
    '100105' => '扣除金额失败，请重新操作',
    //AdminGroupController
    '100200' => '没有此组可编辑',
    '100201' => '没有此组可删除',
    '100202' => '没有此组',
    //ActivityInfosController
    '100300' => '该活动名已存在',
    '100301' => '该活动不存在',
    '100302' => '上传图片时，没有写入文件权限',
    '100303' => '缺少开始时间或结束时间',
    '100304' => '需要排序的活动不存在',
    '100305' => '需要排序的sort相同',
    //ActivityTypeController
    '100400' => '活动分类不存在',
    //ArticlesController
    '100500' => '文章名已存在',
    '100501' => '文章不存在',
    '100502' => '上传图片失败',
    '100503' => '需要排序的sort相同',
    //BankController
    '100600' => '银行不存在',
    //ConfiguresController
    '100700' => '该配置键名已存在',
    '100701' => '该配置不存在',
    '100702' => '主级配置不可修改状态',
    '100703' => '生成奖期时间的配置不存在,请先到运营管理-系统设置页面，添加 sign = generate_issue_time 的奖期自动生成时间配置。',
    //MenuController
    '100800' => '菜单名已存在',
    '100801' => '编辑保存有误',
    //RechargeCheckController
    '100900' => '当前状态非待审核状态',
    '100901' => '请先添加 sign=artificial_recharge的人工充值 帐变类型',
    '100902' => '给用户添加金额时失败，请重新操作',
    //RegionController
    '101000' => '县级行政区编码错误',
    '101001' => '行政区已经存在',
    //ArtificialRechargeController
    '101100' => '用户不存在',
    '101101' => '您目前没有充值额度',
    '101102' => '您的充值额度不足',
    //AccountChangeTypeController
    '101200' => '帐变类型不存在',
    '101201' => 'sign已存在',
    //FundOperationController
    '101300' => '管理员不存在',
    '101301' => '该管理员没有人工充值权限',
    '101302' => '请先添加管理员充值额度系统配置',
    //RouteController
    '101400' => '该路由标题已存在',
    '101401' => '该路由不存在',
    '101402' => '该路由所属meun不存在',
    '101403' => '该路由已存在',
    //FrontendWebRouteController
    '101500' => '该路由已存在',
    '101501' => '该路由不存在',
    //FrontendAllocatedModel
    '101600' => '模块名称已存在',
    '101601' => '模块en_name已存在',
    '101602' => '模块不存在',
    '101603' => '不可在第3级的模块下添加下级',
    //LotteriesController
    '101700' => '彩种不存在',
    '101701' => '该玩法组下不存在玩法',
    '101702' => '该玩法行下不存在玩法',
    '101703' => '该玩法不存在',
    //HomepageRotationChartController
    '101800' => '轮播图标题已存在',
    '101801' => '缺少跳转地址',
    '101802' => '缺少活动id',
    '101803' => '图片上传失败',
    '101804' => '需要编辑的轮播图不存在',
    '101805' => '删除原图片失败',
    '101806' => '需要删除的轮播图不存在',
    '101807' => '需要排序的轮播图不存在',
    '101808' => '轮播图绑定的活动不存在',
    '101809' => '需要排序的sort相同',
    //HomepageController
    '101900' => '需要编辑的首页模块不存在',
    '101901' => '该首页模块不可修改值',
    '101902' => '该首页模块不可修改展示数量',
    '101903' => '该配置不存在',
    '101904' => '上传图片失败',
    '101905' => 'ico模块不存在',
    //PopularLotteriesController
    '102000' => '该彩票已经是热门彩票',
    '102001' => '图片不能为空',
    '102002' => '上传图片失败',
    '102003' => '需要编辑的热门彩票不存在',
    '102004' => '该热门彩票类型不需要修改图片',
    '102005' => '需要删除的热门彩票不存在',
    '102006' => '需要排序的热门彩票不存在',
    '102007' => '需要排序的两个热门彩票类型不一致',
    '102008' => '需要排序的热门彩票不存在',
    '102009' => '需要排序的sort相同',
    '102010' => '该类型的热门彩票已存在',
    '102011' => '选择的彩种不存在',
    //NoticeController
    '102100' => '标题已存在',
    '102101' => '该公告不存在',
    '102102' => '需要排序的公告不存在',
    '102103' => '需要排序的sort相同',
    //MethodLevelController
    '102200' => '该玩法不存在',
    '102201' => '该玩法等级不存在',
    '102202' => '该玩法等级已存在',
];
