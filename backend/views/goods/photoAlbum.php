<?php
header('content-type:text/html;charset=utf-8');
?>
<!--图片上传-->
<?php if (Yii::$app->user->can("goods/add-images")):?>
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
<?php endif;?>
<table class="table">
    <thead>
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="images">
    <?php if ($goodsGalleryList != null):?>
    <?php foreach ($goodsGalleryList as $goodsGallery):?>
        <tr>
            <td><?=\yii\bootstrap\Html::img($goodsGallery->path,["width"=>"100px","height"=>"100px"])?></td>
            <td>
                <?php if (Yii::$app->user->can("goods/dele-images")):?>
                <?=\yii\bootstrap\Html::button("删除",["class"=>"del btn btn-warning"])?>
                <?php endif;?>
            </td>
        </tr>
    <?php endforeach;?>
    <?php endif;?>
    </tbody>
</table>
<?php
/**
 * 显示目录层级 zTree 插件配置
 * @var $this \yii\web\View
 */
$this->registerCssFile("@web/webuploader/webuploader.css");
$this->registerJsFile("@web/webuploader/webuploader.js",[
    "depends"=>\yii\web\JqueryAsset::className()]);
$url = \yii\helpers\Url::to(["add-images","id"=>$id]);
$url1 = \yii\helpers\Url::to(["dele-images"]);
$this->registerJs(<<<JS
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
    //>>==========================================================================
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on( 'uploadSuccess', function(file,data) {
           console.log(file);
           console.log(data);
           if (data.success){
               $("#img").attr("src",data.src);
               $("#images").append("<tr><td><img src="+data.src+" width='100px' height='100px'></td><td><button class='del btn btn-warning'>删除</button></td></tr>")
           }else {
               $("#img").attr("src",data.alt);
           }
        });
        //>>设置显示默认图片(修改时回显)
        var defaultLogo = $("#goods-logo").val();
        $("#img").attr("src",defaultLogo)
        //>>删除文件
        $("#images").delegate(".del","click",function() {
          var src = $(this).closest("tr").find("img").attr("src");
          var that = $(this)
          $.post("{$url1}",{"src":src},function(data) {
            if (data==1){
                alert("删除成功")
                that.closest("tr").fadeOut()
            }
          })
        })
JS
);