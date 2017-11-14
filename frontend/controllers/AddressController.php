<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 16:05
 */

namespace frontend\controllers;


use frontend\models\Address;
use frontend\models\Memeber;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;

class AddressController extends Controller
{
    //>>显示列表
    public function actionList(){
        //>>判断用户是否已登录
        if(\Yii::$app->user->isGuest){
            return $this->redirect(Url::to(["/member/login"]));
        }

        //>>查找当前用户收藏的地址
        $address = new Address();
        $addressList = $address->find()->where(["memeber_id"=>\Yii::$app->user->identity->id])->orderBy('status desc')->all();
        //>>显示页面
        return $this->render("list",[
            "addressList"=>$addressList
        ]);
    }
    //>>添加地址
    public function actionAdd(){
        //>>接收数据
        $request = new Request();
        if ($request->isPost){
            $address = new Address();
            $address->load($request->post(),"");
            if ($address->validate() && $address->add()){
                //>>添加成功,跳转页面
                return $this->redirect("list");
            }else{
                var_dump($address->getErrors());exit();
            }
        }
    }
    //>>删除地址
    public function actionDel(){
        //>>接收数据
        $id = \Yii::$app->request->get("id");
        //>>删除数据
        if (Address::findOne(["id"=>$id])->delete()){
            echo 1;
        }else{
            echo false;
        }
    }
    //>>修改地址
    public function actionUpd(){
        //>>接收参数
        $request = new Request();
        $id = $request->get("id");
        //>>获取该条数据
        $address = Address::find()->where(["id"=>$id])->asArray()->one();
        //>>判断请求方式,接收参数
        if ($request->isPost){
            $model = Address::findOne(["id"=>$request->post("id")]);
            $model->load($request->post(),"");
            if (!$request->post("status")){
                $model->status = 0;
            }
            if ($model->validate() && $model->add()){
                //>>修改成功,跳转页面
                return $this->redirect("list");
            }else{
                var_dump($address->getErrors());exit();
            }
        }
        //>>响应浏览器
        return json_encode($address);
    }
    //>>设置默认地址
    public function actionDefault(){
        //>>接收参数
        $id = \Yii::$app->request->get("id");
        //>>设置默认地址
        $address = Address::findOne(["id"=>$id]);
        $address->status = 1;
        $address->save();
        //>>修改其余地址状态
        $addreses = Address::find()->where(["!=","id",$address->id])->all();
        foreach ($addreses as $addre){
            $addre->status = 0;
            $addre->save();
        }
        return 1;
    }
}