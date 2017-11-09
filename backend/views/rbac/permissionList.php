<?php
header('content-type:text/html;charset=utf-8');
?>
<div class="container">
    <div class="row">
        <h1>权限列表</h1>
    </div>
    <div class="row">
        <table class="table table-striped table-condensed">
            <thead>
            <tr>
                <th>路由</th>
                <th>描述</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($permissionList as $permission):?>
                <tr>
                    <td><?=$permission->name?></td>
                    <td><?=$permission->description?></td>
                    <td>
                        <?=\yii\bootstrap\Html::button("删除",["class"=>"del btn btn-warning btn-xs","route"=>$permission->name])?>
                        <?=\yii\bootstrap\Html::a("修改",\yii\helpers\Url::to(["/rbac/permission-update","name"=>$permission->name]),["class"=>"btn btn-success btn-xs"])?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-lg-2">
            <?=\yii\bootstrap\Html::a("添加",[\yii\helpers\Url::to(["/rbac/permission-add"])],["class"=>"btn btn-info"])?>
        </div>
        <div class="col-lg-8"></div>
        <div class="col-lg-2">
        </div>
    </div>
</div>
<?php
/**
 * @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(["/rbac/permission-delete"]);
$this->registerJs(<<<JS
$(".del").click(function() {
    if (confirm("删除后数据无法恢复,是否继续")){
        var route = $(this).attr("route");
        var that = $(this);
        $.post("$url",{"route":route},function(data) {
            if (data==1){
                alert("删除成功");
                that.closest("tr").fadeOut();
            }else {
                alert(data);
            }
        })
    }
})
JS
);
