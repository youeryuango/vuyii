<?php


namespace src\controllers;

use common\models\user\UserAdmin;
use src\components\FormatResponse as FR;

class AuthController extends BaseController
{

    protected $accessUrl = ['login'];

    /**
     * @introduce 用户登录，使用个人信息交换 Token 令牌
     * @return object|\yii\web\Response
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/11/8 1:26 上午
     */
    public function actionLogin()
    {
        $request = \Yii::$app->request;
        if(!$request->isPost) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $postData = $request->post();
        if(!isset($postData['username'], $postData['password']) || empty($postData['username']) || empty($postData['password'])) return FR::jsonResponse(FR::CODE_STATUS_PARAMS_ERROR);
        $user = UserAdmin::findByUsername($postData['username']);
        if($user === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '未找到该用户！');
        if(!$user->validatePassword($postData['password'])) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '密码错误！');
        $token = parent::generateToken($user->auth_key);
        $user->verification_token = $token;
        if(!$user->save()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, current($user->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '获取 Token 成功！', compact('token'));
    }
}