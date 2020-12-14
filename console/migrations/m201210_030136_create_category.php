<?php

use yii\db\Migration;

/**
 * Class m201210_030136_create_category
 */
class m201210_030136_create_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(60)->notNull()->unique()->comment('名称'),
            'is_use' => $this->tinyInteger(4)->notNull()->defaultValue(1)->comment('状态 0禁用 1启用'),
            'create_time' => $this->dateTime()->notNull()->comment('创建时间'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201210_030136_create_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201210_030136_create_category cannot be reverted.\n";

        return false;
    }
    */
}
