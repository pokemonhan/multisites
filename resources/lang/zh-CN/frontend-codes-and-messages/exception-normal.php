<?php
/**
 * Created by PhpStorm.
 * author: Harris
 * Date: 5/7/2019
 * Time: 8:23 PM
 */

return [
    //ApiMainController
    '100000' => '机器人等不正常客户禁止请求',
    //AuthController
    '100001' => '您没有访问权限',
    '100002' => '账号密码错误',
    '100003' => '旧密码不匹配',
    '100004' => '没有此用户',
    '100005' => '你已多次登录暂时不能登录',
    '100007' => '新密码与旧密码相同',
    '100008' => '两次密码不一致',
    '100009' => '原密码不正确',
    '100010' => 'type非法',
    '100011' => '修改密码失败',
    '100012' => '修改资料失败',
    '100013' => '资金密码已存在',
    '100014' => '您已被禁止登陆',
    //UserHandleController
    '100100' => '更改密码已有申请',
    '100101' => '更改资金密码已有申请',
    '100102' => '没有此条信息',
    //UserBankCardController
    '100200' => '银行卡所有者非本人,不可操作',
    '100201' => '删除绑定银行卡失败',
    '100202' => '可绑定的银行卡数量已经是最大，不能继续添加',
    //LotteriesController
    '100300' => '对不起, 玩法:methodName位置不正确!',
    '100301' => '对不起, 模式:mode, 不存在!',
    '100302' => '对不起, 奖金组:prizeGroup, 游戏未开放!',
    '100303' => '对不起, 奖金组:prizeGroup, 用户不合法!',
    '100304' => '对不起, 玩法:methodId, 注单号码不合法!',
    '100305' => '对不起, 倍数:times, 不合法!',
    '100306' => '对不起, 单价不符合!',
    '100307' => '对不起, 总价不符合!',
    '100309' => '对不起, 追号奖期不正确!',
    '100310' => '对不起, 奖期已过期!',
    '100312' => '对不起, 当前余额不足!',
    '100313' => '对不起, 账号不完整!',
    '100314' => '非法操作',
    '100315' => '该追号不存在或当前状态不可停止追号',
    '100316' => '该投注当前状态不可撤销',
    '100317' => '您已被禁止投注',
    '100318' => '您已被禁止资金操作',
    //HomepageController
    '100400' => '当前模块为关闭状态',
    '100401' => '非法操作',
    //CryptMiddleware
    '100500' => 'data参数传入格式错误',
    '100501' => '解密参数缺失',
    '100502' => 'IV解密错误',
    '100503' => 'KEY解密错误',
    '100504' => '解压JSON数据失败',
    '100505' => 'AES解密失败',

];
