<?php

namespace src\controllers;

use Yii;
use src\components\FormatResponse as FR;
use common\models\user\UserAdmin;
use common\models\user\search\UserAdminSearch;
use src\controllers\BaseController;
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $count = $dataProvider->getTotalCount();
        $data = $dataProvider->getModels();
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '获取数据成功！', compact('data', 'count'));
    }

    /**
     * Displays a single UserAdmin model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserAdmin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserAdmin();

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return json_encode(['code'=>0,'msg'=>'添加成功！']);
            } else {
                $errors = implode('<br>',$model->getFirstErrors());
                return json_encode(['code'=>201,'msg'=>$errors]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing UserAdmin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return json_encode(['code'=>0,'msg'=>'修改成功！']);
            }else{
                $errors = implode('<br>',$model->getFirstErrors());
                return json_encode(['code'=>201,'msg'=>$errors]);
            }
        }else{
            return $this->render('update', [
                'model' => $model,
            ]);

        }
    }

    /**
     * Deletes an existing UserAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if($model->delete()){
            $res = ['code'=>0,'msg'=>'success'];
        }else{
            $res = ['code'=>201,'msg'=>'系统异常，删除失败！'];
        }

        return json_encode($res);
    }

    /**
     * Finds the UserAdmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserAdmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserAdmin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
