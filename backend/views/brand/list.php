<?php
header('content-type:text/html;charset=utf-8');
?>
<div class="container">
    <div class="row">
        <h1>品牌列表</h1>
    </div>
    <div class="row">
        <div>
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th>#</th>
                    <th>品牌名称</th>
                    <th>品牌简介</th>
                    <th>品牌Logo</th>
                    <th>排序</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($brandList as $brand):?>
                    <tr>
                        <td><?=$brand->id?></td>
                        <td><?=$brand->name?></td>
                        <td><?=$brand->intro?></td>
                        <td>
                            <?=\yii\bootstrap\Html::img($brand->logo,["style"=>"height:40px;width:40px","class"=>"img-circle"])?>
                        </td>
                        <td><?=$brand->sort?></td>
                        <td><?=$brand->status==0?"隐藏":"显示"?></td>
                        <td>
                            <?php if (Yii::$app->user->can("brand/del")):?>
                            <a href="javascript:;" class="del btn btn-warning btn-xs">删除</a>
                            <?php endif; ?>
                            <?php if (Yii::$app->user->can("brand/upd")):?>
                            <a href="<?=\yii\helpers\Url::to(["/brand/upd","id"=>$brand->id])?>" class="btn btn-success btn-xs">更新</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2">
        </div>
        <div class="col-lg-8"></div>
        <div class="col-lg-2">
            <?php echo \yii\widgets\LinkPager::widget([
                "pagination"=>$pager,
                "maxButtonCount"=>3
            ])?>
        </div>
    </div>
</div>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerJs(<<<JS
    $(".del").click(function() {
      if (confirm("删除后数据无法恢复,是否继续?")){
          var id = $(this).closest("tr").find("td:first-child").text();
          var that = $(this);
          $.post("/brand/del",{"id":id},function(data) {
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