<?php

use yii\db\Migration;

/**
 * Handles the creation of table `artice_category`.
 */
class m171103_073616_create_artice_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('artice_category', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment("类名"),
            'intro'=>$this->text()->notNull()->comment("简介"),
            'sort' =>$this->integer()->notNull()->comment("排序"),
            'status' => $this->smallInteger(1)->notNull()->comment("状态")
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('artice_category');
    }
}
