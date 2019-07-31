<?php

/* ========================================================================
 * 会话类
 * 主要功能：在会话的常规操作
 * ======================================================================== */

class Session
{
    public function __construct()
    {
        $this->start();
    }

    //开启session
    public function start()
    {
        if($this->getIsActive()){
            return;
        }

        @session_start();

        if($this->getIsActive()){
            return;
        }else{
            return 'session failed to start';
        }
    }


    /**
     * 设置session
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }


    /**
     * 获取session
     * @param $key
     * @return string
     */
    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
    }


    //session 是否处于活跃状态
    public function getIsActive()
    {
        return session_status() === PHP_SESSION_ACTIVE;  //PHP_SESSION_ACTIVE = 2
    }


    //获取session 数量
    public function getCount()
    {
        return count($_SESSION);
    }


    /**
     * 修改session ID
     * @param $value
     */
    public function setId($value)
    {
        session_id($value);
    }


    //获取session 唯一ID
    public function getId()
    {
        return session_id();
    }


    /**
     * 删除session
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
            return TRUE;
        }

        return TRUE;
    }


    //强行销毁所有session, 注：不建议使用
    public function destory()
    {
        if($this->getIsActive()){
            @session_unset();
            @session_destroy();
        }
    }


    //暂停session 的写入
    public function close()
    {
        if($this->getIsActive()){
            @session_write_close();
        }
    }


    //暂停session 的写入
    public function __destruct()
    {
        $this->close();
    }
}