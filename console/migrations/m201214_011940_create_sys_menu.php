<?php

use yii\db\Migration;

/**
 * Class m201214_011940_create_sys_menu
 */
class m201214_011940_create_sys_menu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sys_menu}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->comment('菜单名称'),
            'pid' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('父级菜单编号'),
            'route' => $this->string(255)->comment('路由地址'),
            'icon' => $this->string(255)->comment('图标'),
            'sort' => $this->string(100)->notNull()->defaultValue(100)->comment('排序')
        ]);
        $this->createIndex('pid', '{{%sys_menu}}', 'pid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201214_011940_create_sys_menu cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201214_011940_create_sys_menu cannot be reverted.\n";

        return false;
    }
    */
}
