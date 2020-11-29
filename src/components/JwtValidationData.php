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
        parent::init();
    }
}