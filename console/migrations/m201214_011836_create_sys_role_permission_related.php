<?php

use yii\db\Migration;

/**
 * Class m201214_011836_create_sys_role_permission_related
 */
class m201214_011836_create_sys_role_permission_related extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sys_role_permission_related}}', [
            'id' => $this->primaryKey()->unsigned(),
            'role_id' => $this->integer()->unsigned()->notNull()->comment('角色编号'),
            'permission_id' => $this->integer()->unsigned()->notNull()->comment('权限编号'),
        ]);
        $this->createIndex('role_permission', '{{%sys_role_permission_related}}', ['permission_id', 'role_id'], true);
        $this->createIndex('permission_id', '{{%sys_role_permission_related}}', 'permission_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201214_011836_create_sys_role_permission_related cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201214_011836_create_sys_role_permission_related cannot be reverted.\n";

        return false;
    }
    */
}
