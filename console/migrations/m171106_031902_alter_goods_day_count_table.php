<?php

use yii\db\Migration;

class m171106_031902_alter_goods_day_count_table extends Migration
{
    public function safeUp()
    {
        $this->addPrimaryKey("","goods_day_count","day");
    }

    public function safeDown()
    {
        echo "m171106_031902_alter_goods_day_count_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171106_031902_alter_goods_day_count_table cannot be reverted.\n";

        return false;
    }
    */
}
