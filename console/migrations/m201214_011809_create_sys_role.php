<?php

use yii\db\Migration;

/**
 * Class m201214_011809_create_sys_role
 */
class m201214_011809_create_sys_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sys_role}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(100)->unique()->notNull()->comment('角色名称'),
            'sort' => $this->string(100)->notNull()->defaultValue(100)->comment('排序'),
            'tips' => $this->string(255)->comment('备注')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201214_011809_create_sys_role cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201214_011809_create_sys_role cannot be reverted.\n";

        return false;
    }
    */
}
