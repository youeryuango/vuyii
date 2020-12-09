<?php

use yii\db\Migration;

/**
 * Class m201208_162213_create_post
 */
class m201208_162213_create_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(200)->notNull()->unique()->comment('标题'),
            'desc' => $this->string(200)->notNull()->comment('简介'),
            'keywords' => $this->text()->notNull()->comment('关键词'),
            'content' => $this->text()->notNull()->comment('内容'),
            'category_id' => $this->integer()->notNull()->comment('类型编号'),
            'status' => $this->tinyInteger(4)->notNull()->defaultValue(1)->comment('状态 0禁用 1启用'),
            'base_view_num' => $this->integer()->notNull()->defaultValue(0)->comment('基础阅读量'),
            'actual_view_num' => $this->integer()->notNull()->defaultValue(0)->comment('实际阅读量'),
            'user_id' => $this->integer()->notNull()->comment('创建人编号'),
            'create_time' => $this->dateTime()->notNull()->comment('创建时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201208_162213_create_post cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201208_162213_create_post cannot be reverted.\n";

        return false;
    }
    */
}
