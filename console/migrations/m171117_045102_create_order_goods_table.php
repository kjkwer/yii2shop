<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m171117_045102_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->comment("订单id"),
            'goods_id' => $this->integer()->comment("商品id"),
            'goods_name' => $this->string(255)->comment("商品名称"),
            'logo' => $this->string(255)->comment("商品名称"),
            'price' => $this->decimal(9,2)->comment("商品价格"),
            'amount' => $this->integer()->comment("商品数量"),
            'total' => $this->decimal(9,2)->comment("小计")
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
