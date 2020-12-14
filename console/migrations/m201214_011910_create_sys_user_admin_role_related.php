<?php

use yii\db\Migration;

/**
 * Class m201214_011910_create_sys_user_admin_role_related
 */
class m201214_011910_create_sys_user_admin_role_related extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sys_user_admin_role_related}}', [
            'id' => $this->primaryKey()->unsigned(),
            'role_id' => $this->integer()->unsigned()->notNull()->comment('角色编号'),
            'user_id' => $this->integer()->unsigned()->notNull()->comment('用户编号'),
        ]);
        $this->createIndex('role_permission', '{{%sys_user_admin_role_related}}', ['user_id', 'role_id'], true);
        $this->createIndex('user_id', '{{%sys_user_admin_role_related}}', 'user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201214_011910_create_sys_user_admin_role_related cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201214_011910_create_sys_user_admin_role_related cannot be reverted.\n";

        return false;
    }
    */
}
