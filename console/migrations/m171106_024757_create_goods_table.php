<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m171106_024757_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            'name' => $this->string(20)->notNull()->comment("商品名称"),
            'sn' => $this->string(20)->notNull()->comment("商品SN号"),
            'logo' => $this->string(100)->notNull()->comment("商品Logo"),
            'goods_category_id' => $this->integer()->notNull()->comment("分类ID"),
            'brand_id' => $this->integer()->notNull()->comment("品牌ID"),
            'market_price' => $this->decimal(10,2)->notNull()->comment('市场价格'),
            'shop_price' => $this->decimal(10,2)->notNull()->comment('商品价格'),
            'stock' => $this->integer()->notNull()->comment("库存"),
            'is_on_sale' => $this->smallInteger(1)->notNull()->comment("是否上线"),
            'status' => $this->smallInteger()->notNull()->comment("商品状态"),
            'sort' => $this->integer()->comment("排序"),
            'create_time' => $this->integer()->notNull()->comment("创建时间"),
            'view_times' => $this->integer()->comment("浏览次数")
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
