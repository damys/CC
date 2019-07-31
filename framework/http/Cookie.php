<?php

/**
 * Class Cookie
 */
Class Cookie
{
    private $time = 0;

    //默认自动加载
    public function __construct()
    {
        $this->time = time() + 1800;   // 3分钟
    }

    /**
     * 设置cookie
     * @param $cookieName
     * @param string $cookieValue
     * @param string $time         cookie存活时间, 秒
     * @param string $path         cookie路径
     * @param string $domain       默认空，不用管
     * @param bool $secure         是否只能通过https协议访问
     * @param bool $httponly       是否只能通过http协议读取，如果true，客户端js无法获取cookie值
     * @return bool
     */
    function set($cookieName, $cookieValue = '', $time = '',  $path = '', $domain = '', $secure = FALSE, $httponly = FALSE)
    {
        if($time == '' || !is_numeric($time)){
            // 默认为3分钟
            $time = $this->time;
        } else {
            $time = time() + $time;
        }

        if($cookieName != ''){
            setcookie($cookieName, $cookieValue, $time,  $path, $domain, $secure, $httponly);
            return TRUE;
        } else {
            return FALSE;
        }
    }


    /**
     * 获取cookie, 如果没有则返回空
     * @param $cookieName
     * @return string
     */
    function get($cookieName)
    {
        if($cookieName != ''){
            return empty($_COOKIE[$cookieName]) ? '' : $_COOKIE[$cookieName];
        }else{
            return '';
        }
    }


    //获取cookie 的个数
    public function getCount()
    {
        return count($_COOKIE);
    }


    /**
     * 删除掉一个cookie
     * @param $cookieName
     * @return bool
     */
    function delete($cookieName)
    {
        if($cookieName != ''){
            $this->set($cookieName, '', 0);
            return TRUE;
        }else{
            return FALSE;
        }
    }



    /**
     * 清除所有cookie
     */
    public function clear()
    {
        if(isset($_COOKIE)){
            unset($_COOKIE);
        }
    }
}