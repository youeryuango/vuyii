<?php

namespace common\models\post;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $desc 简介
 * @property int $status 状态
 * @property string $content 内容
 * @property string $create_time 创建时间
 * @property int $user_id 创建人
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'desc', 'content', 'create_time', 'user_id'], 'required'],
            [['status', 'user_id'], 'integer'],
            [['content'], 'string'],
            [['create_time'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['desc'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'desc' => '简介',
            'status' => '状态',
            'content' => '内容',
            'create_time' => '创建时间',
            'user_id' => '创建人',
        ];
    }
}
