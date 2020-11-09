<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\user\UserAdmin */
?>
<div class="layui-fluid user-admin-view">
    <?= DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'layui-table','lay-even'=>'','lay-size'=>'sm'],
    'attributes' => [
              'id',
          'username',
          'auth_key',
          'verification_token',
          'password_hash',
          'email:email',
          'status',
          'create_time',
    ],
    ]) ?>
</div>
