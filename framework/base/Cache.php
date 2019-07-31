<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/2/26 0026
 * Time: 11:52
 */

class Cache
{
    // 缓存目录
    static $cache_path = '../runtime/caches/';
    static $is_cache = 1;


    /**
     * set cache
     * @param $name
     * @param array $data
     * @param int $extime
     * @return bool
     */
    public static function set($name, $data = array(), $extime = 0)
    {
        if (!$name || !is_string($name)){ return false; }

        $name     = md5($name);
        $filepath = self::$cache_path .substr($name,0,2).'/';
        $filename = $filepath.$name;

        if($data === null) {
            @unlink($filename); return true;
        }

        if (!is_readable($filepath) || is_file($filepath)) {
            mkdir($filepath, 0766,true);
        }

        $insert = [
            'time' => $extime,
            'data' => $data
        ];

        $insert = serialize($insert);
        file_put_contents($filename, $insert);

        return true;
    }

    /**
     * get cache
     * @param $name
     * @return bool
     */
    public static function get($name)
    {
        if (!$name || !is_string($name)){ return false; }

        $name     = md5($name);
        $filepath = self::$cache_path .substr($name,0,2).'/';
        $filename = $filepath.$name;

        if (file_exists($filename) && self::$is_cache) {
            $content = file_get_contents($filename);
            $arr = unserialize($content);

            if (time() - $arr['time'] > filemtime($filename) && $arr['time'] != 0) {
                unlink($filename);
                return false;
            }

            return $arr['data'];
        }
        else {
            return false;
        }
    }

    /**
     * @param $name
     * @param array $data
     * @param int $extime
     * @return bool
     */
    public static function caches($name, $data = array(), $extime = 0)
    {
        if (!$name || !is_string($name)){ return false; }

        $name     = md5($name);
        $filepath = self::$cache_path .substr($name,0,2).'/';
        $filename = $filepath.$name;

        //读缓存文件
        if (empty($extime) && empty($data) && $data!==null) {
            if (file_exists($filename) && self::$is_cache) {
                $content = file_get_contents($filename);
                $arr = unserialize($content);

                if (time() - $arr['time'] > filemtime($filename) && $arr['time'] != 0) {
                    // 删除文件
                    unlink($filename);
                    return false;
                }

                return $arr['data'];
            }
            else {
                return false;
            }
        }
        // 创建缓存文件
        else {
            if($data === null){@unlink($filename); return true;};

            if (!is_readable($filepath) || is_file($filepath)) {
                mkdir($filepath, 0766,true);
            }

            $insert = [
                'time' => $extime,
                'data' => $data
            ];

            $insert = serialize($insert);
            file_put_contents($filename, $insert);

            return true;
        }
    }
}