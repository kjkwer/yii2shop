<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m171103_055823_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment("品牌名称"),
            'intro' => $this->text()->notNull()->comment("品牌简介"),
            'logo' => $this->string(255)->notNull()->comment("品牌LOGO"),
            'sort' =>$this->integer()->notNull()->comment("排序"),
            'status' => $this->smallInteger(1)->notNull()->comment("状态")
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
