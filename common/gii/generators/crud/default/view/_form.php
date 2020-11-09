<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    html{
        background-color: #ffffff;
    }
</style>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'options'=>[
            'class'=>'layui-form model-form',
            'lay-filter'=>'user-admin-filter',
            'id'=>'<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form'
        ],
        'fieldConfig'=>[
            'template' => '<div class="layui-form-item">{label}<div class="layui-input-block">{input}</div></div>',
            'labelOptions' => ['class' => 'layui-form-label'],
            'inputOptions' => ['class'=>'layui-input'],
            'options'=>['tag'=>false],
            'is_show_required'=>true
        ]
    ]); ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
        if (!empty($generator->formFields)) {
            if (!in_array($attribute, $generator->formFields)) {
                continue;
            }
        }
        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
    }
} ?>
    <div class="layui-form-item text-right">
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
        <button class="layui-btn" lay-filter="btnSubmit" lay-submit>保存</button>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
<script>
    layui.use(['layer', 'form', 'admin', 'laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var laydate = layui.laydate;
        var admin = layui.admin;
        var url = $('#<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form').attr('action');
        // admin.iframeAuto();  // 让当前iframe弹层高度适应
        // 表单提交事件
        form.on('submit(btnSubmit)', function (data) {
            layer.load(2);
            admin.req(url,data.field,function (data) {
                layer.closeAll('loading');
                if (data.code == 0) {
                    top.layer.msg(data.msg, {icon: 1});
                    admin.putTempData('t-<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form-ok', true);  // 操作成功刷新表格
                    // 关闭当前iframe弹出层
                    admin.closeThisDialog();
                } else {
                    top.layer.msg(data.msg, {icon: 2});
                }
            },'post');

            return false;
        });


        laydate.render({
            elem: '#date'
        });
    });
</script>
