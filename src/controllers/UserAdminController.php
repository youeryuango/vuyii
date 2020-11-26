<?php

namespace src\controllers;

use Yii;
use src\components\FormatResponse as FR;
use common\models\user\UserAdmin;
use common\models\user\search\UserAdminSearch;
use yii\helpers\Json;
/**
 * UserAdminController implements the CRUD actions for UserAdmin model.
 */
class UserAdminController extends BaseController
{
    /**
     * Lists all UserAdmin models.
     * @author 张文杰
     * @slogan 岁岁平，岁岁安，岁岁平安
     * @return object|\yii\web\Response
     */
    public function actionIndex()
    {
        if(!Yii::$app->request->isGet) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $searchModel = new UserAdminSearch();
        dd(json_decode(Yii::$app->request->get('pageArgs'), true));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $count = $dataProvider->getTotalCount();
        $list = $dataProvider->getModels();
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '获取数据成功！', compact('list', 'count'));
    }

    /**
     * Get a data set according to the condition.
     * @param integer $id
     * @author 张文杰
     * @slogan 岁岁平，岁岁安，岁岁平安
     * @return object|\yii\web\Response
     */
    public function actionRead($id)
    {
        if(!Yii::$app->request->isGet) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = UserAdmin::findOne($id);
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该记录！');
        $datum = $model->toArray();
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '获取数据成功！', compact('datum'));
    }

    /**
     * Creates a new UserAdmin model.
     * If creation is successful, you have to create a record through a POST request.
     * @author 张文杰
     * @slogan 岁岁平，岁岁安，岁岁平安
     * @return object|\yii\web\Response
     */
    public function actionCreate()
    {
        if(!Yii::$app->request->isPost) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = new UserAdmin();
        if(!$model->load(Yii::$app->request->post(), '')) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '载入数据失败！');
        if(!$model->save()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '创建记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '创建记录成功！');
    }

    /**
     * Updates an existing UserAdmin model.
     * If update is successful, you have to update a record through a PUT request.
     * @param integer $id
     * @author 张文杰
     * @slogan 岁岁平，岁岁安，岁岁平安
     * @return object|\yii\web\Response
     */
    public function actionUpdate($id)
    {
        if(!Yii::$app->request->isPut) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = UserAdmin::findOne($id);
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该记录！');
        $params = Json::decode(Yii::$app->request->getRawBody(), true);
        if(!isset($params) || empty($params) || !is_array($params)) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '参数解析失败！');
        if(!$model->load($params, '')) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '载入数据失败！');
        if(!$model->save()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '更新记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '更新记录成功！');
    }

    /**
     * Deletes an existing UserAdmin model.
     * If deletion is successful, you have to delete a record through a DELETE request.
     * @param integer $id
     * @author 张文杰
     * @slogan 岁岁平，岁岁安，岁岁平安
     * @return object|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        if(!Yii::$app->request->isDelete) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = UserAdmin::findOne($id);
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该内容！');
        if(!$model->delete()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '删除记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '删除记录成功！');
    }
}
