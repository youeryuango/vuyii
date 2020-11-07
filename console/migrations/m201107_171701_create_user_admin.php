<?php

use yii\db\Migration;

/**
 * Class m201107_171701_create_user_admin
 */
class m201107_171701_create_user_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_admin}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull()->comment('授权 key'),
            'password_hash' => $this->string()->notNull()->comment('密码 Hash'),
            'email' => $this->string()->notNull()->unique()->comment('邮箱'),
            'status' => $this->tinyInteger(4)->notNull()->defaultValue(1)->comment('状态 0禁用 1启用'),
            'created_time' => $this->dateTime()->notNull()->comment('创建时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201107_171701_create_user_admin cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201107_171701_create_user_admin cannot be reverted.\n";

        return false;
    }
    */
}
