<?php
header('content-type:text/html;charset=utf-8');
?>
<div class="container">
    <div class="row">
        <h1>角色列表</h1>
    </div>
    <div class="row">
        <table class="table table-striped table-condensed">
            <thead>
            <tr>
                <th>名称</th>
                <th>描述</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($roleList as $role):?>
                <tr>
                    <td><?=$role->name?></td>
                    <td><?=$role->description?></td>
                    <td>
                        <?php if (Yii::$app->user->can("rbac/role-delete")):?>
                        <?=\yii\bootstrap\Html::button("删除",["class"=>"del btn btn-warning btn-xs","name"=>$role->name])?>
                        <?php endif;?>
                        <?php if (Yii::$app->user->can("rbac/role-update")):?>
                        <?=\yii\bootstrap\Html::a("修改",\yii\helpers\Url::to(["/rbac/role-update","name"=>$role->name]),["class"=>"btn btn-success btn-xs"])?>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-lg-2">
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
$url = \yii\helpers\Url::to(["/rbac/role-delete"]);
$this->registerJs(<<<JS
$(".del").click(function() {
    if (confirm("删除后数据无法恢复,是否继续")){
          var name = $(this).attr("name");
          var that = $(this)
          $.post("{$url}",{"name":name},function(data) {
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