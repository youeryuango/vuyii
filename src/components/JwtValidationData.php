<?php


namespace src\components;

class JwtValidationData extends \sizeg\jwt\JwtValidationData
{
    /**
     * @introduce
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/11/7 3:20 下午
     */
    public function init()
    {
        $this->validationData->setIssuer(ISSUER);
        $this->validationData->setAudience(AUDIENCE);
        $this->validationData->setId(CLAIM_ID);
//        $authHeader = \Yii::$app->request->getHeaders()->get('authorization');
//        if(isset($authHeader) && !empty($authHeader) && strstr($authHeader, 'Bearer')){
//            preg_match('/^Bearer\s+(.*?)$/', $authHeader, $matches);
//            if(isset($matches[1]) && !empty($matches[1])){
//                $token = \Yii::$app->jwt->getParser()->parse((string) $matches[1]);
//                dd($token->getClaims());
//                foreach (array_keys($token->getClaims()) as $claim) {
//                    switch ($claim){
//                        case 'iss':
//                            if($token->getClaim($claim) !== ISSUER) throw new UnauthorizedHttpException();
//                        case 'aud':
//                            if($token->getClaim($claim) !== AUDIENCE) throw new UnauthorizedHttpException();
//                        case 'jti':
//                            if($token->getClaim($claim) !== CLAIM_ID) throw new UnauthorizedHttpException();
//                        case '':
//                    }
//                }
//            }
//        }
        parent::init();
    }
}