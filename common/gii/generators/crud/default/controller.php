<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use src\components\FormatResponse as FR;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;s
use yii\helpers\Json;
/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
{
    /**
     * Lists all <?= $modelClass ?> models.
     * @author 张文杰
     * @slogan 岁岁平，岁岁安，岁岁平安
     * @return object|\yii\web\Response
     */
    public function actionIndex()
    {
        if(!Yii::$app->request->isGet) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $count = $dataProvider->getTotalCount();
        $list = $dataProvider->getModels();
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '获取数据成功！', compact('list', 'count'));
    }

    /**
     * Get a data set according to the condition.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @author 张文杰
     * @slogan 岁岁平，岁岁安，岁岁平安
     * @return object|\yii\web\Response
     */
    public function actionRead(<?= $actionParams ?>)
    {
        if(!Yii::$app->request->isGet) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = <?= $modelClass ?>::findOne(<?= $actionParams ?>);
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该记录！');
        $datum = $model->toArray();
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '获取数据成功！', compact('datum'));
    }

    /**
     * Creates a new <?= $modelClass ?> model.
     * If creation is successful, you have to create a record through a POST request.
     * @author 张文杰
     * @slogan 岁岁平，岁岁安，岁岁平安
     * @return object|\yii\web\Response
     */
    public function actionCreate()
    {
        if(!Yii::$app->request->isPost) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = new <?= $modelClass ?>();
        if(!$model->load(Yii::$app->request->post(), '')) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '载入数据失败！');
        if(!$model->save()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '创建记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '创建记录成功！');
    }

    /**
     * Updates an existing <?= $modelClass ?> model.
     * If update is successful, you have to update a record through a PUT request.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @author 张文杰
     * @slogan 岁岁平，岁岁安，岁岁平安
     * @return object|\yii\web\Response
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        if(!Yii::$app->request->isPut) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = <?= $modelClass ?>::findOne(<?= $actionParams ?>);
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该记录！');
        $params = Yii::$app->request->getBodyParams();
        if(!isset($params) || empty($params) || !is_array($params)) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '参数解析失败！');
        if(!$model->load($params, '')) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '载入数据失败！');
        if(!$model->save()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '更新记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '更新记录成功！');
    }

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, you have to delete a record through a DELETE request.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @author 张文杰
     * @slogan 岁岁平，岁岁安，岁岁平安
     * @return object|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        if(!Yii::$app->request->isDelete) return FR::jsonResponse(FR::CODE_STATUS_REQUEST_ERROR);
        $model = <?= $modelClass ?>::findOne(<?= $actionParams ?>);
        if($model === null) return FR::jsonResponse(FR::CODE_STATUS_FAILED, '没有找到该内容！');
        if(!$model->delete()) return FR::jsonResponse(FR::CODE_STATUS_SYSTEM_ERROR, '删除记录失败！原因为:' . current($model->getFirstErrors()));
        return FR::jsonResponse(FR::CODE_STATUS_SUCCESS, '删除记录成功！');
    }
}
