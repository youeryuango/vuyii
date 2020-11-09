<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\user\UserAdmin */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    html{
        background-color: #ffffff;
    }
</style>
<div class="user-admin-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'class'=>'layui-form model-form',
            'lay-filter'=>'user-admin-filter',
            'id'=>'user-admin-form'
        ],
        'fieldConfig'=>[
            'template' => '<div class="layui-form-item">{label}<div class="layui-input-block">{input}</div></div>',
            'labelOptions' => ['class' => 'layui-form-label'],
            'inputOptions' => ['class'=>'layui-input'],
            'options'=>['tag'=>false],
            'is_show_required'=>true
        ]
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'verification_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'create_time')->textInput() ?>

    <div class="layui-form-item text-right">
        <button class="layui-btn layui-btn-primary" type="button" ew-event="closeDialog">取消</button>
        <button class="layui-btn" lay-filter="btnSubmit" lay-submit>保存</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    layui.use(['layer', 'form', 'admin', 'laydate'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        var form = layui.form;
        var laydate = layui.laydate;
        var admin = layui.admin;
        var url = $('#user-admin-form').attr('action');
        // admin.iframeAuto();  // 让当前iframe弹层高度适应
        // 表单提交事件
        form.on('submit(btnSubmit)', function (data) {
            layer.load(2);
            admin.req(url,data.field,function (data) {
                layer.closeAll('loading');
                if (data.code == 0) {
                    top.layer.msg(data.msg, {icon: 1});
                    admin.putTempData('t-user-admin-form-ok', true);  // 操作成功刷新表格
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
