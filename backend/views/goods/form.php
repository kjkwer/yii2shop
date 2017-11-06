<?php
header('content-type:text/html;charset=utf-8');
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($goodsModel,"name")->textInput();
echo $form->field($goodsModel,"sn")->textInput(['readonly'=>'true']);
echo $form->field($goodsModel,"logo")->hiddenInput();
?>
    <!--图片上传-->
    <div id="uploader-demo">
        <!--用来存放item-->
        <div id="fileList" class="uploader-list"></div>
        <div id="filePicker">选择图片</div>
        <div><img id="img" src="" width="100px" height="100px"></div>
    </div>
<?php
echo $form->field($goodsModel,"goods_category_id")->hiddenInput();
?>
    <!--选择分类-->
    <div>
        <ul id="treeDemo" class="ztree"></ul>
    </div>
<?php
echo $form->field($goodsModel,"brand_id")->dropDownList(\backend\models\Brand::getOneArray());
echo $form->field($goodsModel,"market_price")->textInput();
echo $form->field($goodsModel,"shop_price")->textInput();
echo $form->field($goodsModel,"stock")->textInput();
echo $form->field($goodsModel,"is_on_sale",["inline"=>true])->radioList([0=>"下架",1=>"在售"]);
echo $form->field($goodsModel,"sort")->textInput();
echo $form->field($goodsIntroModel,"content")->widget(\kucha\ueditor\UEditor::className(),[]);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

/**
 * 显示目录层级 zTree 插件配置
 * @var $this \yii\web\View
 */
$this->registerCssFile("@web/zTree/css/zTreeStyle/zTreeStyle.css");
$this->registerCssFile("@web/webuploader/webuploader.css");
$this->registerJsFile("@web/webuploader/webuploader.js",[
    "depends"=>\yii\web\JqueryAsset::className()]);
$url = \yii\helpers\Url::to("uploads");
$this->registerJsFile("@web/zTree/js/jquery.ztree.core.js",[
    "depends" => \yii\web\JqueryAsset::className()
]);
$arr = \yii\helpers\Json::encode(array_merge(\backend\models\GoodsCategory::getAll()));
$this->registerJs(
    <<<JS
    //>>===============zTree插件=============================
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
                  $("#goods-goods_category_id").val(id);
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
            var node = zTreeObj.getNodeByParam("id", {$goodsModel->goods_category_id}, null);
            //>>2 选中节点
            zTreeObj.selectNode(node);
    //>>===================图片上传======================
        // 选完文件后，是否自动上传。
        var uploader = WebUploader.create({
            auto: true,
            // swf文件路径
            swf: '/js/Uploader.swf',
            // 文件接收服务端。
            server: '{$url}',
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#filePicker',
            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/gif,image/jpg,image/jpeg,image/bmp,image/png'
            }
        });
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on( 'uploadSuccess', function(file,data) {
           //console.log(file);
           //console.log(data);
           if (data.success){
               $("#img").attr("src",data.src);
               $("#goods-logo").val(data.src);
           }else {
               $("#img").attr("src",data.alt);
           }
        });
        //>>设置显示默认图片(修改时回显)
        var defaultLogo = $("#goods-logo").val();
        $("#img").attr("src",defaultLogo)
JS
);