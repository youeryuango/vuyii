<?php


namespace src\controllers;

use common\models\user\UserAdmin;
use src\components\FormatResponse;
use Yii;
use sizeg\jwt\JwtHttpBearerAuth;
use src\traits\Helper;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\UnauthorizedHttpException;

class BaseController extends Controller
{
    use Helper;

    protected $tokenStr;
    protected $token;
    protected $user;
    protected $accessUrl = [];
    public $enableCsrfValidation = false;
    /**
     * @introduce 行为校验
     * @return array
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/11/7 3:42 下午
     */
    public function behaviors()
    {
        Yii::$app->response->headers->set("Access-Control-Allow-Origin", "*");
        Yii::$app->response->headers->set("Access-Control-Allow-Methods", "DELETE, POST, PUT, GET");
        Yii::$app->response->headers->set("Access-Control-Expose-Headers", "x-pagination-page-count, x-pagination-total-count, x-pagination-per-page,x-pagination-current-page");
        Yii::$app->response->headers->set("Access-Control-Allow-Headers", " Content-Type, authorization");
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
     * @return bool|object|\yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/11/30 12:36 上午
     */
    public function beforeAction($action)
    {
        $currController = $action->controller->id;
        $currAction = $action->id;
        if(!in_array($currAction, $this->accessUrl)){
            $this->_parseToken();
            // token 失效
            if(!$this->token) {
                FormatResponse::jsonResponse(FormatResponse::CODE_UNAUTHORIZED);
                return false;
            }
            if(!empty($this->token)){
                $this->user = $this->_getUser();
            }
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
                $this->tokenStr = $matches[1];
                $this->token = (new JwtHttpBearerAuth())->loadToken($this->tokenStr);
            }
        }
    }

    /**
     * @introduce 获取当前账户
     * @return UserAdmin|\yii\web\IdentityInterface|null
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/11/27 6:14 下午
     */
    private function _getUser()
    {
        return UserAdmin::findIdentityByAccessToken($this->token);
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