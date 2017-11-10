<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 13:34
 */

namespace backend\models;


use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;
class MenuQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}