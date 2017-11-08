<?php
header('content-type:text/html;charset=utf-8');
?>
<div class="container">
    <div class="row">
        <h1>会员列表</h1>
    </div>
    <div class="row">
        <table>
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <td>#</td>
                    <td>用户名</td>
                    <td>邮箱</td>
                    <td>状态</td>
                    <td>登录时间</td>
                    <td>操作</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($userList as $user):?>
                    <tr>
                        <td><?=$user->id?></td>
                        <td><?=$user->username?></td>
                        <td><?=$user->email?></td>
                        <td><?=$user->status==1?"启用":"禁用"?></td>
                        <td><?=date("Y-m-d H:i:s",$user->updated_at)?></td>
                        <td>
                            <?=\yii\bootstrap\Html::button("删除",["class"=>"del btn btn-warning btn-xs"])?>
                            <?=\yii\bootstrap\Html::a("修改",[\yii\helpers\Url::to(["upd","id"=>$user->id])],["class"=>"btn btn-info btn-xs"])?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </table>
    </div>
    <div class="row">
        <div class="col-lg-2">
            <?=\yii\bootstrap\Html::a("添加",[\yii\helpers\Url::to(["add"])],["class"=>"btn btn-primary btn-lg"])?>
        </div>
        <div class="col-lg-8"></div>
        <div class="col-lg-2">
            <?php
            echo \yii\widgets\LinkPager::widget([
                "pagination"=>$pager,
                "maxButtonCount"=>3
            ])
            ?>
        </div>
    </div>
</div>
<?php
/**
 * @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(["/user/del"]);
$this->registerJs(<<<JS
//>>删除数据
$(".del").click(function() {
    if (confirm("删除后数据无法恢复,是否继续")){
          var id = $(this).closest("tr").find("td:first-child").text();
  var that = $(this);
  $.post("{$url}",{"id":id},function(data) {
    if (data==1){
        alert("删除成功");
        that.closest("tr").fadeOut()
    }else {
        alert("删除失败:".data)
    }
  })
    }

})
JS
);