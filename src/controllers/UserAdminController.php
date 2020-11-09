<?php

namespace src\controllers;

use Yii;
use src\components\FormatResponse as FR;
use common\models\user\UserAdmin;
use common\models\user\search\UserAdminSearch;
use src\controllers\BaseController;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserAdminController implements the CRUD actions for UserAdmin model.
 */
class UserAdminController extends BaseController
{

    /**
     * Lists all UserAdmin models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(!Yii::$app->request->isGet) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $searchModel = new UserAdminSearch();
        $params = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($params);
        $count = $dataProvider->getTotalCount();
        $list = $dataProvider->getModels();
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '获取数据成功！', compact('list', 'count'));
    }

    public function actionRead($id)
    {
        if(!Yii::$app->request->isGet) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = UserAdmin::findOne($id);
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该记录！');
        $datum = $model->toArray();
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '获取数据成功！', compact('datum'));
    }

    public function actionCreate()
    {
        if(!Yii::$app->request->isPost) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = new UserAdmin();
        if(!$model->load(Yii::$app->request->post(), '')) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '载入数据失败！');
        if(!$model->save()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '创建记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '创建记录成功！');
    }

    public function actionUpdate($id)
    {
        if(!Yii::$app->request->isPut) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = UserAdmin::findOne($id);
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该记录！');
        $params = Json::decode(Yii::$app->request->getRawBody(), true);
        if(!$model->load($params, '')) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '载入数据失败！');
        if(!$model->save()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '更新记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '更新记录成功！');
    }

    public function actionDelete($id)
    {
        if(!Yii::$app->request->isDelete) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = UserAdmin::findOne($id);
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该内容！');
        if(!$model->delete()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '删除记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '删除记录成功！');
    }

    public function actionChangeAttr($id, $attr, $currVal, $toVal)
    {
        if(!Yii::$app->request->isPut) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = UserAdmin::findOne($id);
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该记录！');
        if(!$model->hasAttribute($attr)) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该属性！');
        if($model->$attr !== (string)$currVal) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '当前属性值已发生更改！');
        $model->$attr = $toVal;
        if(!$model->save()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '更新记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '更新记录成功！');
    }

}
