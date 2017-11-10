<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m171110_033457_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'name' => $this->string()->notNull()->comment("商品名称"),
            'parent_id' => $this->integer()->notNull(),
            'url' => $this->string(50)->notNull(),
            'sort' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
