<?php

use yii\db\Migration;

class m171113_104110_alter_address_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn("address","status","integer");
    }

    public function safeDown()
    {
        echo "m171113_104110_alter_address_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171113_104110_alter_address_table cannot be reverted.\n";

        return false;
    }
    */
}
