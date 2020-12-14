<?php

namespace src\controllers;

use common\models\sys\SysUserAdminRoleRelated;
use Yii;
use src\components\FormatResponse as FR;
use common\models\user\UserAdmin;
use common\models\user\search\UserAdminSearch;
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
        $postData = Yii::$app->request->post();
        if(!isset($postData['password']) || empty($postData['password'])) {
            $postData['password'] = '123456';
        }else{
            if(mb_strlen($postData['password']) < 6 || mb_strlen($postData['password']) > 20) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '密码必须是 6-20 位！');
        }
        if(!$model->load($postData, '')) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '载入数据失败！');
        $model->auth_key    = Yii::$app->security->generateRandomString(10);
        $model->create_time = date('Y-m-d H:i:s');
        $model->password_hash = Yii::$app->security->generatePasswordHash($postData['password']);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if(!$model->save()) throw new \Exception('创建记录失败！原因为:' . current($model->getFirstErrors()), FR::CODE_STATUS_SYSTEM_ERROR);
            if(isset($postData['roles']) && !empty($postData['roles'])) SysUserAdminRoleRelated::relatedUserRole($postData['roles'], $model->id);
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            return FR::jsonResponse($e->getCode(), $e->getMessage());
        }
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
        $params =Yii::$app->request->getBodyParams();
        if(isset($params['status']) && $this->user->id === (int)$id){
            return FR::jsonResponse(FR::CODE_STATUS_FAILED, '您无法更改自己的可用状态！');
        }
        if(!isset($params) || empty($params) || !is_array($params)) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '参数解析失败！');
        if(!$model->load($params, '')) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '载入数据失败！');
        if(isset($params['password']) && !empty($params['password'])){
            if(mb_strlen($params['password']) < 6 || mb_strlen($params['password']) > 20) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '密码必须是 6-20 位！');
            $model->password_hash = Yii::$app->security->generatePasswordHash($params['password']);
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if(!$model->save()) throw new \Exception('更新记录失败！原因为:' . current($model->getFirstErrors()), FR::CODE_STATUS_SYSTEM_ERROR);
            if(isset($params['roles']) && !empty($params['roles'])) SysUserAdminRoleRelated::relatedUserRole($params['roles'], $model->id, true);
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            return FR::jsonResponse($e->getCode(), $e->getMessage());
        }
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '更新记录成功！');
    }

    public function actionHasRelatedRoles($userId)
    {
        if(!Yii::$app->request->isGet) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $roles = SysUserAdminRoleRelated::hasRelatedRole($userId);
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '获取数据成功！', compact('roles'));
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
        if($this->user->id === (int)$id){
            return FR::jsonResponse(FR::CODE_STATUS_FAILED, '您无法删除自身！');
        }
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该内容！');
        if(!$model->delete()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '删除记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '删除记录成功！');
    }
}
