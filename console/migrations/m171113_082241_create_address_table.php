<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m171113_082241_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'username' => $this->string(20)->notNull()->comment("用户名"),
            'memeber_id' => $this->integer()->notNull()->comment("用户ID"),
            'provinces' => $this->string(50)->notNull()->comment("省份"),
            'cities' => $this->string(50)->notNull()->comment("城市"),
            'areas' => $this->string(50)->notNull()->comment("区域"),
            'address' => $this->string(255)->notNull()->comment("详细地址"),
            'tel' => $this->char(11)->notNull()->comment("电话号码"),
        ]);
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
