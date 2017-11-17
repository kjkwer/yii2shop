<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m171117_030748_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->notNull()->comment("用户ID"),
            'name' => $this->string(50)->notNull()->comment("收货人"),
            'province' => $this->string(20)->notNull()->comment("省份"),
            'city' => $this->string(20)->notNull()->comment("城市"),
            'area' => $this->string(20)->notNull()->comment("区域"),
            'address' => $this->string(255)->notNull()->comment("地址"),
            'tel' => $this->char(11)->notNull()->comment("电话"),
            'delivery_id' => $this->smallInteger()->notNull()->comment("配送方式id"),
            'delivery_name' => $this->string(50)->notNull()->comment("配送方式名称"),
            'delivery_price' => $this->decimal()->notNull()->comment("配送价格"),
            'payment_id' => $this->smallInteger()->notNull()->comment("支付方式id"),
            'payment_name' => $this->string(50)->notNull()->comment("支付方式名称"),
            'total' => $this->decimal()->notNull()->comment("订单总价"),
            'status' => $this->smallInteger()->notNull()->comment("订单状态"),
            'trade_no' => $this->string(50)->notNull()->comment("第三方支付交易号"),
            'create_time' => $this->integer()->notNull()->comment("创建时间")
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
