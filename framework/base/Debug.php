<?php
/** ******************************************************************************
 * 调试模式类，用于在开发阶段调试程序使用
 * ******************************************************************************/

namespace framework\base;

class Debug
{
    static $includeFile =[];
    static $info        =[];
    static $sqls        =[];
    static $startTime   =0;                     // 保存脚本开始执行时的时间（以微秒的形式保存）
    static $stopTime    =0;                      // 保存脚本结束执行时的时间（以微秒的形式保存）

    public static $msg = array(
        E_WARNING           =>'运行时警告',
        E_NOTICE            =>'运行时提醒',
        E_STRICT            =>'编码标准化警告',
        E_RECOVERABLE_ERROR =>'捕获致命错误',
        E_PARSE             =>'解析错误',
        E_USER_ERROR        =>'自定义错误',
        E_USER_WARNING      =>'自定义警告',
        E_USER_NOTICE       =>'自定义提醒',
        'Unkown'            =>'未知错误'
    );


    /**
     * 在脚本开始处调用获取脚本开始时间的微秒值
     */
    public static function start()
    {
        self::$startTime = microtime(true);
    }


    /**
     *在脚本结束处调用获取脚本结束时间的微秒值
     */
    public static function stop()
    {
        self::$stopTime= microtime(true);
    }


    /**
     *返回同一脚本中两次获取时间的差值
     * 计算后以4舍5入保留4位返回
     */
    public static function spent()
    {
        return round((self::$stopTime - self::$startTime) , 4);
    }


    /**
     * 错误捕捉手
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     */
    public static function Catcher($errno, $errstr, $errfile, $errline)
    {
        if(!isset(self::$msg[$errno])) $errno='Unkown';

        if($errno == E_NOTICE || $errno == E_USER_NOTICE) {
            $color="#777";
        }
        else {
            $color="red";
        }

        $mess='<span style="color:'.$color.'">';
        $mess.='<strong>'.self::$msg[$errno]."</strong> [在文件 {$errfile} 中,第 $errline 行]：";
        $mess.=$errstr;
        $mess.='</span>';

        self::addmsg($mess);
    }


    /**
     * 添加调试消息
     * @param	string	$msg	调试消息字符串
     * @param	int	$type	消息的类型
     */
    static function addmsg($msg, $type=0)
    {
        if(defined("DEBUG") && DEBUG==1)
        {
            switch($type){
                case 0:
                    self::$info[]=$msg;
                    break;
                case 1:
                    self::$includeFile[]=$msg;
                    break;
                case 2:
                    self::$sqls[]=$msg;
                    break;
                default:
                    break;
            }
        }
    }


    /**
     * 输出调试消息
     * 将系统错误信息写入文件日志
     */
    public static function message()
    {
        echo '<div style="text-align:left;padding:10px;position: relative; z-index:100">';
        echo '<div style="font-size:11px;color:#777;padding:10px; background:#F5F5F5;border:1px dotted #777;">';
        echo '<div style="float:left;width:100%;padding: 2px 0;"><span style="float:left;width:200px;">运行信息( <span style="color: red">'.self::spent().' </span>秒)</span><span onclick="this.parentNode.parentNode.style.display=\'none\'" style="cursor:pointer;float:right;width:35px; padding:0 2px;background:#555;border:1px solid #666;color:white">关闭X</span></div><br>';
        echo '<ul style="margin:0px;padding:0;list-style:none">';

        self::writeLog(PHP_EOL.'运行时间: '.self::spent().'秒');

        if(count(self::$includeFile) > 0){
            echo '<strong>[自动包含]</strong>';
            foreach(self::$includeFile as $file){
                echo '<li style="padding-left: 20px">'.date('Y-m-d H:i:s', time()).' '.$file.'</li>';
            }
        }

        if(count(self::$info) > 0 ){
            echo '<br><strong>[系统信息]</strong>';
            foreach(self::$info as $info){
                echo '<li style="padding-left: 20px">'.date('Y-m-d H:i:s', time()).' '.$info.'</li>';

                $info = $str=preg_replace("/<(\/?span.*?)>||<(\/?strong.*?)>/si","", $info);   // 过滤 span， strong标签
                self::writeLog( '[系统信息]'.date('Y-m-d H:i:s', time()).' '.$info);
            }
        }

        if(count(self::$sqls) > 0) {
            echo '<br><strong>[SQL语句]</strong>';
            foreach(self::$sqls as $sql){
                echo '<li style="padding-left: 20px">'.date('Y-m-d H:i:s', time()).' '.$sql.'</li>';

                if(\Config::get('debug_log_sql_is')) {
                    self::writeLog('[SQL语句]'.$sql);
                }
            }
        }

        echo '</ul></div></div>';
    }


    /**
     * 写入日志信息到文件
     * @param string $msg  日志信息
     * 注：目录结构：年/月/年月日_db_log.txt
     */
    static function writeLog($msg = '')
    {
        if(\Config::get('debug_log_is') && !empty($msg))
        {
            // 拼装日志目录
            $log_path   = \Config::get('debug_log_path') . date('Y', time()) .'/'. date('m', time()) . '/';

            // 创建多级目录
            if(!file_exists($log_path)) mkdir($log_path, 0777, true);

            $handle = fopen($log_path . '/' . date('Ymd', time()) ."_debug_log.txt", "a+");

            $text = $msg . PHP_EOL;
            fwrite($handle, $text);

            fclose($handle);
        }
    }
}
