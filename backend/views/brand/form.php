<?php
header('content-type:text/html;charset=utf-8');
?>
<div>
    <h1>编辑品牌</h1>
</div>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"name")->textInput();
echo $form->field($model,"intro")->textarea();
echo $form->field($model,"logo")->hiddenInput();
?>
<!--图片上穿-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
    <div><img id="img" src="" width="100px" height="100px"></div>
</div>
<?php
echo $form->field($model,"sort")->textInput();
echo $form->field($model,"status",["inline"=>true])->radioList([0=>"隐藏",1=>"显示"]);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile("@web/webuploader/webuploader.css");
$this->registerJsFile("@web/webuploader/webuploader.js",[
    "depends"=>\yii\web\JqueryAsset::className()]);
$url = \yii\helpers\Url::to("upload");
$this->registerJs(<<<JS
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
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
       $("#brand-logo").val(data.src);
   }else {
       $("#img").attr("src",data.alt);
   }
});
//>>设置显示默认图片
var defaultLogo = $("#brand-logo").val();
$("#img").attr("src",defaultLogo)
JS
);