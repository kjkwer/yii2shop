<?php

use yii\db\Migration;

class m171103_082159_alter_artice_table extends Migration
{
    public function safeUp()
    {
        $this->renameTable("artice","article");
        $this->renameTable("artice_category","article_category");
    }

    public function safeDown()
    {
        echo "m171103_082159_alter_artice_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171103_082159_alter_artice_table cannot be reverted.\n";

        return false;
    }
    */
}
