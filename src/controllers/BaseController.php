<?php


namespace src\controllers;

use Yii;
use sizeg\jwt\JwtHttpBearerAuth;
use src\traits\Helper;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\filters\VerbFilter;
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
        header("Access-Control-Allow-Origin: *");
//如果需要设置允许所有域名发起的跨域请求，可以使用通配符 * ，如果限制自己的域名的话写自己的域名就行了。
// 响应类型 *代表通配符，可以指出POST,GET等固定类型
        header('Access-Control-Allow-Methods:* ');
// 响应头设置
        header('Access-Control-Allow-Headers:x-requested-with,content-type');
        $behaviors = parent::behaviors();
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [],
            ],
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Origin' => ['*'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => [],
                ],
            ],
        ];
        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::className(),
                'optional' => [
                    'login'
                ],
            ];
        }
        $behaviors['authenticator'] = [
//            'class' => JwtHttpBearerAuth::class,
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