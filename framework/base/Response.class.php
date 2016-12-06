<?php

/* ========================================================================
 * 通信数据类
 * 主要功能, 按综合方式输出通信数据
 * ======================================================================== */

class Response {
    const JSON = "json";
    /**
     * 按综合方式输出通信数据
     * @param integer $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * @param string $type 数据类型
     * return string
     */
    public static function show($code, $message = '', $data = array(), $type = self::JSON) {
        if(!is_numeric($code)) {
            return '';
        }

        $type = isset($_GET['format']) ? $_GET['format'] : self::JSON;

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
        );

        if($type == 'json') {
            self::json($code, $message, $data);
            exit;
        } elseif($type == 'array') {
            var_dump($result);
        } elseif($type == 'xml') {
            self::xmlEncode($code, $message, $data);
            exit;
        } else {
            // TODO
        }
    }
    /**
     * 按json方式输出通信数据
     * @param integer $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * return string
     */
    public static function json($code, $message = '', $data = array()) {

        if(!is_numeric($code)) {
            return '';
        }

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data
        );

        echo json_encode($result);
        exit;
    }

    /**
     * 按xml方式输出通信数据
     * @param integer $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * return string
     */
    public static function xmlEncode($code, $message, $data = array()) {
        if(!is_numeric($code)) {
            return '';
        }

        $result = array(
            'code' => $code,
            'message' => $message,
            'data' => $data,
        );

        header("Content-Type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<root>\n";

        $xml .= self::xmlToEncode($result);

        $xml .= "</root>";
        echo $xml;
    }

    public static function xmlToEncode($data) {

        $xml = $attr = "";
        foreach($data as $key => $value) {
            if(is_numeric($key)) {
                $attr = " id='{$key}'";
                $key = "item";
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= is_array($value) ? self::xmlToEncode($value) : $value;
            $xml .= "</{$key}>\n";
        }
        return $xml;
    }

}


/*
test---
public function check() {
		$this->params['app_id'] = $appId = isset($_POST['app_id']) ? $_POST['app_id'] : '';
		$this->params['version_id'] = $versionId = isset($_POST['version_id']) ? $_POST['version_id'] : '';
		$this->params['version_mini'] = $versionMini = isset($_POST['version_mini']) ? $_POST['version_mini'] : '';
		$this->params['did'] = $did = isset($_POST['did']) ? $_POST['did'] : '';
		$this->params['encrypt_did'] = $encryptDid = isset($_POST['encrypt_did']) ? $_POST['encrypt_did'] : '';

		if(!is_numeric($appId) || !is_numeric($versionId)) {
			return Response::show(401, '参数不合法');
		}
		// 判断APP是否需要加密
		$this->app = $this->getApp($appId);
		if(!$this->app) {
			return Response::show(402, 'app_id不存在');
		}
		if($this->app['is_encryption'] && $encryptDid != md5($did . $this->app['key'])) {
			return Response::show(403, '没有该权限');
		}
	}


*/