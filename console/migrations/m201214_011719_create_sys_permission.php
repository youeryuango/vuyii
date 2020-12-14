<?php

use yii\db\Migration;

/**
 * Class m201214_011719_create_sys_permission
 */
class m201214_011719_create_sys_permission extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sys_permission}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100)->notNull()->comment('权限名称'),
            'pid' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('父级权限编号'),
            'sort' => $this->string(100)->notNull()->defaultValue(100)->comment('排序'),
            'tips' => $this->string(255)->comment('备注')
        ]);
        $this->createIndex('pid', '{{%sys_permission}}', 'pid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201214_011719_create_sys_permission cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201214_011719_create_sys_permission cannot be reverted.\n";

        return false;
    }
    */
}
