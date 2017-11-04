<?php

use yii\db\Migration;

/**
 * Handles the creation of table `artice`.
 */
class m171103_081113_create_artice_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('artice', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment("名称"),
            'intro'=>$this->text()->comment("简介"),
            'article_category_id'=>$this->integer()->comment("分类id"),
            'sort'=>$this->integer()->comment("排序"),
            'status'=>$this->smallInteger()->comment("状态"),
            'create_time'=>$this->integer()->comment("创建时间"),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('artice');
    }
}
