<?php


namespace src\controllers;

use sizeg\jwt\JwtHttpBearerAuth;
use src\traits\Helper;
use yii\rest\Controller;
class BaseController extends Controller
{
    use Helper;

    protected $token;
    protected $accessUrl = [];
    /**
     * @introduce 行为校验
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
            'optional' => $this->accessUrl,
        ];
        return $behaviors;
    }

    /**
     * @introduce 请求事件之前
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/11/9 5:15 下午
     */
    public function beforeAction($action)
    {
        $currController = $action->controller->id;
        $currAction = $action->id;
        if(!in_array($currAction, $this->accessUrl)){
            $this->_parseToken();
        }
        return parent::beforeAction($action);
    }


    /**
     * @introduce 解析出 token
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/11/9 5:14 下午
     */
    private function _parseToken()
    {
        $authHeader = \Yii::$app->request->getHeaders()->get('authorization');
        if(isset($authHeader) && !empty($authHeader) && strstr($authHeader, 'Bearer')) {
            preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches);
            if(isset($matches[1]) && !empty($matches[1])) {
                $this->token = $matches[1];
            }
        }
    }
    /**
     * @introduce 构造 token
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
            ->expiresAt($time + 3600)
            ->withClaim('auth_key', $authKey)
            ->getToken($signer, $key);
        return (string)$token;
    }
}