<?php

use yii\db\Migration;

/**
 * Class m201214_011755_create_sys_permission_route
 */
class m201214_011755_create_sys_permission_route extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sys_permission_route}}', [
            'id' => $this->primaryKey()->unsigned(),
            'permission_id' => $this->integer()->unsigned()->notNull()->comment('权限编号'),
            'route' => $this->string(255)->notNull()->comment('权限路由'),
        ]);
        $this->createIndex('permission_route', '{{%sys_permission_route}}', ['permission_id', 'route'], true);
        $this->createIndex('permission_id', '{{%sys_permission_route}}', 'permission_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201214_011755_create_sys_permission_route cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201214_011755_create_sys_permission_route cannot be reverted.\n";

        return false;
    }
    */
}
