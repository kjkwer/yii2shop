<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 16:54
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Address extends ActiveRecord
{
    public function rules(){
        return [
            [["username","provinces","cities","areas","address","tel"],"required"],
            [["status"],"safe"]
        ];
    }
    //>>添加数据
    public function add(){
        //>>设置数据
        $this->memeber_id = \Yii::$app->user->identity->id;
        if ($this->status==1){
            $this->status = 1;
        }else{
            $this->status = 0;
        }
        if ($this->save()){
            if ($this->status==1){
                $addreses = self::find()->where(["!=","id",$this->id])->all();
                foreach ($addreses as $address){
                    $address->status = 0;
                    $address->save();
                }
            }
            return true;
        }else{
            return false;
        }
    }
}