<?php
header('content-type:text/html;charset=utf-8');
?>
<div>
    <h1>编辑分类</h1>
</div>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"name")->textInput();
echo $form->field($model,"parent_id")->hiddenInput();
?>
    <div>
        <ul id="treeDemo" class="ztree"></ul>
    </div>
<?php
echo $form->field($model,"intro")->textarea();
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();
/**
 * 显示目录层级 zTree 插件配置
 * @var $this \yii\web\View
 */
$this->registerCssFile("@web/zTree/css/zTreeStyle/zTreeStyle.css");
$this->registerJsFile("@web/zTree/js/jquery.ztree.core.js",[
    "depends" => \yii\web\JqueryAsset::className()
]);
$arr = \yii\helpers\Json::encode(array_merge([["id"=>0,"parent_id"=>0,"name"=>"根目录"]],\backend\models\GoodsCategory::getAll()));
$this->registerJs(
    <<<JS
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
                }
            },
            //>>点击节点时,将该节点ID,放入父目录输入框中
            callback: {
                onClick: function(event, treeId, treeNode) {
                  var id=treeNode.id;
                  $("#goodscategory-parent_id").val(id);
                }
            }
        };
        //>>zTree 的数据属性
        var zNodes ={$arr};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //>>展开所有节点
        zTreeObj.expandAll(true);
        //>>设置节点默认选中(修改回显)
            //>>1 获取节点
            var node = zTreeObj.getNodeByParam("id", {$model->parent_id}, null);
            //>>2 选中节点
            zTreeObj.selectNode(node);
JS
);
