<?php

namespace common\models\sys;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sys_role".
 *
 * @property int $id
 * @property string $name 角色名称
 * @property string $sort 排序
 * @property string|null $tips 备注
 */
class SysRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_role';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'sort'], 'string', 'max' => 100],
            [['tips'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '角色名称',
            'sort' => '排序',
            'tips' => '备注',
        ];
    }

    /**
     * @introduce 获取角色
     * @param null $id
     * @return array|string|\yii\db\ActiveRecord[]
     * @author    张文杰
     * @slogan    岁岁平，岁岁安，岁岁平安
     * @datetime  2020/12/14 12:03 下午
     */
    public static function getRoles($id = null)
    {
        if(!is_null($id)){
            $obj = self::findOne($id);
            return $obj ? $obj->name : '未知';
        }else{
            return self::find()->select('id, name')->asArray()->all();
        }
    }
}
