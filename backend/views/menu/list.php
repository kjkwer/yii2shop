<?php
header('content-type:text/html;charset=utf-8');
?>
<div class="container">
    <div class="row">
        <h1>菜单列表</h1>
    </div>
    <div class="row">
        <table class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>名称</th>
                <th>路由</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($allMenus as $allMenu):?>
                <tr>
                    <td><?=$allMenu->id?></td>
                    <td><?=str_repeat("====",$allMenu->depth).$allMenu->name?></td>
                    <td><?=$allMenu->url?></td>
                    <td>
                        <?=\yii\bootstrap\Html::button("删除",["class"=>"del btn btn-warning btn-xs","menuId"=>$allMenu->id])?>
                        <?=\yii\bootstrap\Html::a("修改",\yii\helpers\Url::to(["/menu/upd","id"=>$allMenu->id]),["class"=>"btn btn-success btn-xs"])?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-lg-2">
            <?=\yii\bootstrap\Html::a("添加",[\yii\helpers\Url::to(["/menu/add"])],["class"=>"btn btn-info"])?>
        </div>
        <div class="col-lg-8"></div>
        <div class="col-lg-2">
        </div>
    </div>
</div>
<?php
/**
 * @ver $this \yii\web\View
 */
$url = \yii\helpers\Url::to(["/menu/del"]);
$this->registerJs(<<<JS
//>>===============================datatables插件============
//$('#table_id_example').DataTable();
//>>===============================删除操作==================
$(".del").click(function() {
    if (confirm("删除后数据无法恢复,是否继续")){
        var id = $(this).attr("menuId");
        var that = $(this);
        $.post("{$url}",{"id":id},function(data) {
          if (data == 1){
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