<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/12
 * Time: 15:21
 */

namespace frontend\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Memeber extends ActiveRecord implements IdentityInterface
{
    public $captcha;
    public $rem;
    public function rules(){
        return [
            [["username","password_hash","email","tel"],"required"],
            [["captcha"],"captcha"],
            [["rem"],"safe"]
        ];
    }
    //>>添加用户
    public function add(){
        if (!$model = self::findOne(["username"=>$this->username])){
            $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password_hash);
            $this->status = 1;
            $this->auth_key = uniqid();
            if ($this->save(false)){
                return true;
            }
        }
        return false;

    }
    //>>附加行为(自动更新添加时间和修改事件)
    public function behaviors(){
        return [
            "timestamp"=>[   //自动更新 添加时间 和 修改时间
                //"class"=>TimestampBehavior::className(),
                //"class"=>TimestampBehavior::className(),
                'class' => TimestampBehavior::className(),
                "attributes"=>[
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ]
            ]
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return self::findOne(["id"=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->auth_key === $authKey;
    }
}