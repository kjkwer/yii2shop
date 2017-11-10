<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/10
 * Time: 11:43
 */

namespace backend\models;


use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Menu extends ActiveRecord
{
    public function attributeLabels(){
        return [
            "name"=>"菜单名",
            "parent_id"=>"父级菜单",
            "url"=>"权限路由",
            "sort"=>"排序"
        ];
    }
    public function rules(){
        return [
            [["name","parent_id","url","sort"],"required"],
            [["sort"],"integer"],
            [["name"],"unique"]
        ];
    }
    //>>取出所有菜单,并将其转换为形式为['id'=>"name",'id'=>'name'...]的一维数组
    public static function getAllToOne(){
        $arr = ArrayHelper::map(self::find()->asArray()->all(),"id","name");
        //>>添加顶级分类
        return ArrayHelper::merge([0=>"顶级目录"],$arr);
    }
    //>>生成导航菜单目录
    public static function menuCategory(){
        //>>创建模型对象
        $menu = self::find();
        $arr = [];
        $arrAll = [];
        //>>获取菜单页面所有数据
        $allMenuRoots = $menu->where(["=","parent_id","0"])->orderBy(["tree"=>"desc","lft"=>"asc","rgt"=>"asc"])->asArray()->all();
        //>>创建目录分级
        foreach ($allMenuRoots as $allMenuRoot){
            $arr["label"] = $allMenuRoot["name"];
            $arr["items"] = [];
            if ($allMenus = $menu->where(["parent_id"=>$allMenuRoot["id"]])->asArray()->all()){
                foreach ($allMenus as $allMenu){
                    if(\Yii::$app->user->can($allMenu["url"])){
                        $arr["items"][] = ["label"=>$allMenu["name"],"url"=>[$allMenu["url"]]];
                    }
                }
            }
            if (!$arr["items"]==[]){
                $arrAll[] = $arr;
            }
        }
        return $arrAll;
    }
    //>>添加数据
    public function add(){
        if ($this->parent_id == 0){
            $this->url = "#";
            if ($this->makeRoot()){
                return true;
            }
        }else{
            $parent = self::findOne(["id"=>$this->parent_id]);
            if ($this->prependTo($parent)){
                return true;
            }
        }
    }
    //>>修改数据
    public function upd(){
        if ($this->parent_id==0){
            $this->url = "#";
            if ($this->getOldAttribute("parent_id")==0){
                if ($this->save()){
                    return true;
                }
            }else{
                if ($this->makeRoot()){
                    return true;
                }
            }
        }else{
            $parent = self::findOne(["id"=>$this->parent_id]);
            if ($this->prependTo($parent)){
                return true;
            }
        }
    }
    //>>==========嵌套集合插件配置=====================
    public function behaviors() {
        return [
            'tree' => [
                //'class' => NestedSetsBehavior::className(),
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',//要使用到多棵树
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }
    public static function find()
    {
        return new MenuQuery(get_called_class());
    }
}