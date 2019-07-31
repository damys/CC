<?php
/**
 * 以解决“不同子域名下共享session”以及“同一个主域名不同服务器之间共享session”的问题。
 *
 * 修改点1：使用前，请创建数据库表。下面是在MySQL中创建tbl_session表的示例
    CREATE TABLE `db_session` (
        `session_id` varchar(255) binary NOT NULL default '',
        `session_expires` int(10) unsigned NOT NULL default '0',
        `session_data` text,
        PRIMARY KEY  (`session_id`)
    ) ENGINE=MyISAM;
 *
 * 实现自定义session的打开，关闭，读取，写入，销毁，垃圾回收等过程。
 * 修改点2：请对下列需要指定的变量值进行指定
 */

class DBSession
{
    private $host       = null;
    private $username   = null;
    private $password   = null;
    private $dbname     = null;
    private $db_table   = null;

    private $db_handle  = null;
    private $lifeTime;
    private $session    = null;

    public function __construct($confs)
    {
        $this->host       = $confs['host'];
        $this->username   = $confs['username'];
        $this->password   = $confs['password'];
        $this->dbname     = $confs['dbname'];
        $this->db_table   = 'db_session';

        $this->session    = $confs['sesson'];
    }

    function open($savePath, $sessName)
    {
        // 生命周期-自定义， 否则使用默认时间
        if($this->session['gc_maxlifetime']){
            @ini_set("session.gc_maxlifetime", $this->session['gc_maxlifetime']);
        }

        // 清除概率
        if($this->session['gc_probability'] && $this->session['gc_divisor'] ){
            @ini_set("session.gc_probability", $this->session['gc_probability']);
            @ini_set("session.gc_divisor", $this->session['gc_divisor']);
        }

        // 获取配置文件中的 get session-lifetime
//      $this->lifeTime = get_cfg_var("session.gc_maxlifetime");
        $this->lifeTime = ini_get('session.gc_maxlifetime');

        // open database-connection
        $db_handle = new MySQLi($this->host,$this->username,$this->password,$this->dbname);

        // return success
        if(!$db_handle)
            return false;

        $this->db_handle = $db_handle;
        return true;
    }

    function close()
    {
        $this->gc(ini_get('session.gc_maxlifetime'));
        return mysqli_close($this->db_handle);
    }

    function read($sessID)
    {
        $res = mysqli_query($this->db_handle, "SELECT session_data AS d FROM ".$this->db_table."
            WHERE session_id = '$sessID' AND session_expires > ".time());

        if($row = mysqli_fetch_assoc($res)){
            return $row['d'];
        }

        return "";
    }

    function write($sessID, $sessData)
    {
        // new session-expire-time
        $newExp = time() + $this->lifeTime;

        // is a session with this id in the database?
        $res = mysqli_query($this->db_handle, "SELECT * FROM ".$this->db_table."
            WHERE session_id = '$sessID'");

        // if yes,
        if(mysqli_num_rows($res))
        {
            // ...update session-data
            mysqli_query($this->db_handle, "UPDATE ".$this->db_table."
                SET session_expires = '$newExp',
                session_data = '$sessData'
                WHERE session_id = '$sessID'");

            if(mysqli_affected_rows($this->db_handle)){
                return true;
            }
        } else {
            // if no session-data was found
            mysqli_query($this->db_handle, "INSERT INTO ".$this->db_table." (
                session_id, session_expires, session_data)
                VALUES('$sessID', '$newExp', '$sessData')");
            if(mysqli_affected_rows($this->db_handle)){
                return true;
            }
        }
        return false;
    }

    function destroy($sessID)
    {
        mysqli_query($this->db_handle, "DELETE FROM ".$this->db_table." WHERE session_id = '$sessID'");

        // if session was deleted, return true,
        if(@mysqli_affected_rows($this->db_handle)){
            return true;
        }

        return false;
    }

    function gc($sessMaxLifeTime)
    {
        // delete old sessions
        mysqli_query($this->db_handle,"DELETE FROM ".$this->db_table." WHERE session_expires < ".time());

        return mysqli_affected_rows($this->db_handle);
    }
}



/**
 * 指定session有效的域名
 * 修改点3：使用前，请将下面的.domain.com修改为网站的主域名（例如： .qq.com）,请注意前面个有一个'.'
 *
 * 为区分不同的项目，在入口时指定域名作用域
 */
// define("MAIN_DOMAIN", "a.ah.com");   // a.aihuagrp.com 指定一个，ah.com 多个


/**
 * 不同子域名下共享session信息
 *
 * 修改点4：使用前，请将下面的false修改为true
 * COOKIE_DOMAIN = false 表示：禁止该功能（默认禁止）
 * COOKIE_DOMAIN = true  表示：启用该功能（开启前提：需要定义MAIN_DOMAIN常量）
 */
define("COOKIE_DOMAIN", true);
if (defined("COOKIE_DOMAIN") && COOKIE_DOMAIN)
{
    if (defined("MAIN_DOMAIN")){
        @ini_set("session.cookie_domain", MAIN_DOMAIN);
    }
}


/**
 * 同一个主域名，不同服务器之间共享session信息
 *
 * 修改点5：使用前，请将下面的false修改为true
 * COOKIE_DOMAIN = false 表示：禁止该功能（默认禁止）
 * COOKIE_DOMAIN = true  表示：启用该功能（开启前提：需要建立mysql数据表）
 */
define("USER_SESSION", true);
if (defined("USER_SESSION") && USER_SESSION)
{
    @ini_set("session.save_handler", "user");
    $session = new DBSession($GLOBALS['CONF']);
    @session_module_name("user");
    @session_set_save_handler(
        array(&$session, "open"),
        array(&$session, "close"),
        array(&$session, "read"),
        array(&$session, "write"),
        array(&$session, "destroy"),
        array(&$session, "gc"));
}

session_start();