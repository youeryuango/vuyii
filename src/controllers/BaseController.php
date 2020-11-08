<?php


namespace src\controllers;

use sizeg\jwt\JwtHttpBearerAuth;
use src\traits\Helper;
use yii\rest\Controller;
class BaseController extends Controller
{
    use Helper;

    /**
     * @introduce
     * @return array
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/11/7 3:42 下午
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerAuth::class,
            'optional' => [
                'login',
            ],
        ];
        return $behaviors;
    }

    /**
     * @introduce 构造token
     * @param $authKey string 用户授权 key
     * @return string
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/11/7 4:29 下午
     */
    protected static function generateToken($authKey)
    {
        $jwt    = \Yii::$app->jwt;
        $signer = $jwt->getSigner('HS256');
        $key    = $jwt->getKey();
        $time   = time();
        $token  = $jwt->getBuilder()
            ->issuedBy(ISSUER)
            ->permittedFor(AUDIENCE)
            ->identifiedBy(CLAIM_ID, true)
            ->issuedAt($time)
            ->expiresAt($time + 300)
            ->withClaim('auth_key', $authKey)
            ->getToken($signer, $key);
        return (string)$token;
    }
}