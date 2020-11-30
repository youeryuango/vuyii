<?php


namespace src\components;


use yii\base\Request;
use yii\di\Instance;
use yii\helpers\Json;
use yii\web\Response;

class FormatResponse
{
    const CODE_STATUS_SUCCESS = 10000;
    const CODE_STATUS_FAILED  = 10001;
    const CODE_STATUS_PARAMS_ERROR  = 10002;
    const CODE_STATUS_SYSTEM_ERROR  = 10003;
    const CODE_STATUS_REQUEST_ERROR = 10004;
    const CODE_UNAUTHORIZED    = 401;

    /**
     * 状态码与提示信息的映射
     * @var array
     */
    public static $codeMap = [
          self::CODE_STATUS_SUCCESS => '请求成功！',
          self::CODE_STATUS_FAILED  => '请求失败！',
          self::CODE_STATUS_PARAMS_ERROR  => '参数异常！',
          self::CODE_STATUS_SYSTEM_ERROR  => '系统异常！',
          self::CODE_STATUS_REQUEST_ERROR => '错误的请求方式！',
          self::CODE_UNAUTHORIZED         => '您无此权限！',
    ];

    /**
     * @introduce    格式化返回
     * @param $code  int    状态码
     * @param $msg   string 消息提示
     * @param $data  array  业务数据
     * @param $extra array  全局附加数据
     * @return object|response
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/11/7 6:40 下午
     */
    public static function jsonResponse($code, $msg = '', $data = [], $extra = [])
    {
        if(array_key_exists($code, self::$codeMap) && empty($msg)) $msg = self::$codeMap[$code];
        $response = Instance::ensure('response', \yii\base\Response::className());
        if($code === self::CODE_UNAUTHORIZED) $response->statusCode = self::CODE_UNAUTHORIZED;
        $response->format = Response::FORMAT_JSON;
        $response->data = compact('code', 'msg', 'data', 'extra');
        return $response;
    }
}