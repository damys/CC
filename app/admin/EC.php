<?php

/**
 * 错误可以做为国际化

code>10000 业务逻辑错误

1 开头为通用错误
2 商品类错误
3 主题类错误
4 Banner类错误
5 类目类错误
6 用户类错误
8 订单类错误

10000 通用参数错误
10001 资源未找到
10002 未授权（令牌不合法）
10003 尝试非法操作（自己的令牌操作其他人数据）
10004 授权失败（第三方应用账号登陆失败）
10005 授权失败（服务器缓存异常）

20000 请求商品不存在
30000 请求主题不存在
40000 Banner不存在
50000 类目不存在

60000 用户不存在
60001 用户地址不存在

80000 订单不存在
80001 订单中的商品不存在，可能已被删除
80002 订单还未支付，却尝试发货
80003 订单已支付过
 *
 */

class EC
{
    /**
     * code<1000 系统级别
     */
    const SUCCESS       = [0, 'Success', 200];
    const ERR_UNKNOWN   = [1, '未知错误', 200];
    const ERR_URL       = [2, '访问的接口不存在', 200];
    const ERR_PARAMS    = [3, '参数错误', 200];
    const ERR_TOKEN     = [4, 'Token过期或无效Token', 200];

    const ERR_FORBIDDEN = [403, '权限不限', 403];
    const ERR_400       = [400, 'Request Error', 400];
    const ERR_404       = [404, 'Not Found', 404];
    const ERR_500       = [500, 'Sorry, We make a mistake', 500];

    /**
     * code 1000-1100 常规
     */
    const ERR_EMPTY       = [1001, '不能为空', 200];
    const ERR_INTEGER     = [1002, '必须为整型', 200];
    const ERR_ARRAY       = [1003, '必须为数组', 200];
    const ERR_STRING      = [1004, '必须为字符串', 200];
    const ERR_FILE_EXISTS = [1005, '文件不存在', 200];
    const ERR_FILE        = [1006, '必须为文件', 200];


    /**
     * code 1100-2000 管理用户相关的错误码
     */
    const ERR_M_PASSWORD         = [1101, '管理员密码错误', 200];
    const ERR_M_AND_PASSWORD     = [1102, '管理员或密码不正确', 200];
    const ERR_M_UPDATE_PASSWORD  = [1103, '密码输入错误次数过多', 200];

    const ERR_M_ADD              = [1300, '添加管理员失败', 200];
    const ERR_M_DELETE           = [1301, '删除管理员失败', 200];
    const ERR_M_UPDATE           = [1302, '更新状态失败', 200];
    const ERR_M_FORBIDDEN        = [1303, '该管理员已禁用', 200];

    /**
     * code 4000-5000 用户相关的错误码
     */
    const ERR_U_PASSWORD  = [2001, '用户员密码错误', 200];


    /**
     * code >10000 业务逻辑错误
     */
}