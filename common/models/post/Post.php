<?php

namespace common\models\post;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $desc 简介
 * @property string $keywords 关键词
 * @property string $content 内容
 * @property int $category_id 类型编号
 * @property int $status 状态 0禁用 1启用
 * @property int $base_view_num 基础阅读量
 * @property int $actual_view_num 实际阅读量
 * @property int $user_id 创建人编号
 * @property string $create_time 创建时间
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
            [['title', 'desc', 'keywords', 'content', 'category_id', 'user_id', 'create_time'], 'required'],
            [['keywords', 'content'], 'string'],
            [['category_id', 'status', 'base_view_num', 'actual_view_num', 'user_id'], 'integer'],
            [['create_time'], 'safe'],
            [['title', 'desc'], 'string', 'max' => 200],
            [['title'], 'unique'],
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
            'keywords' => '关键词',
            'content' => '内容',
            'category_id' => '类型编号',
            'status' => '状态 0禁用 1启用',
            'base_view_num' => '基础阅读量',
            'actual_view_num' => '实际阅读量',
            'user_id' => '创建人编号',
            'create_time' => '创建时间',
        ];
    }
}
