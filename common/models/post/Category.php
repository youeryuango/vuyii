<?php

namespace common\models\post;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name 名称
 * @property int $is_use 状态 0禁用 1启用
 * @property string $create_time 创建时间
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'create_time'], 'required'],
            [['is_use'], 'integer'],
            [['create_time'], 'safe'],
            [['name'], 'string', 'max' => 60],
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
            'name' => '名称',
            'is_use' => '状态 0禁用 1启用',
            'create_time' => '创建时间',
        ];
    }
}
