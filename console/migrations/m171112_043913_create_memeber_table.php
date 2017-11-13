<?php

use yii\db\Migration;

/**
 * Handles the creation of table `memeber`.
 */
class m171112_043913_create_memeber_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('memeber', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull()->comment('用户名'),
            'auth_key' => $this->string(32)->notNull()->comment('自动登录验证密钥'),
            'password_hash' => $this->string(100)->notNull()->comment('密码'),
            'email' => $this->string(100)->notNull()->comment('邮箱'),
            'tel' => $this->char(11)->notNull()->comment('电话'),
            'last_login_time' => $this->integer()->notNull()->comment('最后登陆时间'),
            'last_login_ip' => $this->integer()->notNull()->comment('最后登陆时间'),
            'status' => $this->smallInteger()->notNull()->comment('状态'),
            'created_at' => $this->integer()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->notNull()->comment('修改时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('memeber');
    }
}
