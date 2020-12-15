<?php

namespace common\models\sys;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sys_user_admin_role_related".
 *
 * @property int $id
 * @property int $role_id 角色编号
 * @property int $user_id 用户编号
 */
class SysUserAdminRoleRelated extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_user_admin_role_related';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role_id', 'user_id'], 'required'],
            [['role_id', 'user_id'], 'integer'],
            [['user_id', 'role_id'], 'unique', 'targetAttribute' => ['user_id', 'role_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role_id' => '角色编号',
            'user_id' => '用户编号',
        ];
    }



    /**
     * @introduce 关联用户角色
     * @param $roleIds
     * @param $userId
     * @param bool $ifUpdate
     * @throws \yii\db\Exception
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/12/14 2:55 下午
     */
    public static function relatedUserRole($roleIds, $userId, $ifUpdate= false)
    {
        $preBatchInsertAry = [];
        if($ifUpdate === true) self::deleteAll(['user_id' => $userId]);
        if(!empty($roleIds)){
            foreach ($roleIds as $roleId) {
                if(SysRole::findOne($roleId) === null) throw new \Exception('创建记录失败！原因为:角色不存在！');
                if(!self::find()->where(['user_id' => $userId, 'role_id' => $roleId])->exists()) {
                    array_push($preBatchInsertAry, [$userId, $roleId]);
                }
            }
            if(!empty($preBatchInsertAry)) {
                $command = Yii::$app->db->createCommand();
                $command->batchInsert(self::tableName(), ['user_id', 'role_id'], $preBatchInsertAry)->execute();
            }
        }
    }

    /**
     * @introduce 获取已经关联的角色
     * @param $userId
     * @return array
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/12/14 3:13 下午
     */
    public static function hasRelatedRole($userId)
    {
        $data = self::find()->where(['user_id' => $userId])->asArray()->all();
        if(!empty($data)) return ArrayHelper::getColumn($data, 'role_id');
        return [];
    }
}
