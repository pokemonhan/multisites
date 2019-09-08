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
    '100005' => '你已多次登录暂时不能登录',
    //UserHandleController
    '100100' => '更改密码已有申请',
    '100101' => '更改资金密码已有申请',
    '100102' => '没有此条信息',
    '100103' => '请先添加 sign=artificial_deduction的人工扣款 帐变类型',
    '100104' => '用户剩余金额少于需要扣除的金额',
    '100105' => '扣除金额失败，请重新操作',
    '100106' => '获取平台信息失败',
    '100107' => '获取商户信息失败',
    '100108' => '系统头像ID错误',
    '100109' => '修改头像失败,请重新操作',
    '100110' => '该用户的上级ID不错在，请联系管理员进行检查',
    '100111' => '该用户不存在',
    '100112' => '该用户信息不完整',
    '100113' => '用户信息有误',
    '100114' => '密码必须为6-16位的字符串',
    '100115' => '密码必须包含字母,强度:弱',
    '100116' => '密码必须包含数字,强度:中',
    '100117' => '密码只能包含数字和字母,强度:强',
    '100118' => '资金密码必须为6-18位的字符串',
    '100119' => '资金密码必须包含字母,强度:弱',
    '100120' => '资金密码必须包含数字,强度:中',
    '100121' => '资金密码只能包含数字和字母,强度:强',
    '100122' => '密码与资金密码不能一致',
    //AdminGroupController
    '100200' => '没有此组可编辑',
    '100201' => '没有此组可删除',
    '100202' => '没有此组',
    '100203' => '获取平台信息失败',
    //ActivityInfosController
    '100301' => '获取平台信息失败',
    //LotterySeriesController
    '100400' => '该系列下存在彩种，请先删除彩种后再删除系列',
    //ArticlesController
    '100502' => '获取平台信息失败',
    //ConfiguresController
    '100701' => '获取平台信息失败',
    '100702' => '生成奖期时间配置失败',
    //MenuController
    '100800' => '菜单名已存在',
    '100801' => '编辑保存有误',
    //RechargeCheckController
    '100900' => '当前状态非待审核状态',
    '100901' => '用户不存在',
    '100902' => '给用户添加金额时失败，请重新操作',
    '100903' => '该管理员不存在',
    '100904' => '该数据不完整',
    '100905' => '这条数据不存在',
    '100906' => '用户信息不完整',
    '100907' => '审核失败，请重新尝试',
    //RegionController
    '101000' => '县级行政区编码错误',
    '101001' => '行政区已经存在',
    //ArtificialRechargeController
    '101100' => '您目前没有充值额度',
    '101101' => '您的充值额度不足',
    '101102' => '给用户人工充值失败,请重新操作',
    '101103' => '用户不存在',
    //FundOperationController
    '101300' => '该管理员没有人工充值权限',
    '101301' => '请先添加 sign=admin_recharge_daily_limit 的管理员充值额度系统配置',
    //RouteController
    '101401' => '该路由不存在',
    //FrontendWebRouteController
    '101500' => '该路由已存在',
    '101501' => '该路由不存在',
    //FrontendAllocatedModel
    '101603' => '不可在第3级的模块下添加下级',
    //LotteriesController
    '101700' => '彩种不存在',
    '101701' => '该玩法组下不存在玩法',
    '101702' => '该玩法行下不存在玩法',
    '101703' => '录号的奖期不存在',
    '101704' => '该奖期已经存在开奖号码,不可重复录号',
    '101705' => 'type非法',
    '101706' => '该奖期暂未结束，不可录号',
    //HomepageBannerController
    '101801' => '图片上传失败',
    '101802' => '获取平台信息失败',
    //HomepageController
    '101900' => 'ico模块不存在',
    '101901' => '获取平台信息失败',
    //PopularLotteriesController
    '102000' => '该类型的热门彩票已存在',
    '102001' => '获取平台信息失败',
    //MethodLevelController
    '102200' => '该玩法等级已存在',
    //AccountChangeType
    '102300' => '字段ID错误,字段不存在',
    //LotteryNoticeController
    '102400' => '该信息不存在',
    //DomainController
    '102500' => '该域名已经存在',
    '102501' => '需要携带要修改的参数',
];
