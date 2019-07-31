<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/9 0009
 * Time: 17:08
 */
class OrderStatusEnum
{
    const UNPAID          = 1;        // 待支付
    const PAID            = 2;        // 已支付
    const DELIVERED       = 3;        // 已发货
    const PAID_BUT_OUT_OF = 4;        // 已支付，但库存不足
}