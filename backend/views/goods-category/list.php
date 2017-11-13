<?php
header('content-type:text/html;charset=utf-8');
?>
<div class="container">
    <div class="row">
        <h1>分类列表</h1>
    </div>
    <div class="row">
        <div>
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th>#</th>
                    <th>目录名称</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($goodsCategoryList as $goodsCategory):?>
                    <tr>
                        <td><?=$goodsCategory->id?></td>
                        <td><?=str_repeat("==>",$goodsCategory->depth).$goodsCategory->name?></td>
                        <td>
                            <?php if (Yii::$app->user->can("goods-category/del")):?>
                            <a href="javascript:;" class="del btn btn-warning btn-xs">删除</a>
                            <?php endif;?>
                            <?php if (Yii::$app->user->can("goods-category/upd")):?>
                            <a href="<?=\yii\helpers\Url::to(["/goods-category/upd","id"=>$goodsCategory->id])?>" class="btn btn-success btn-xs">更新</a>
                            <?php endif;?>
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
          $.post("/goods-category/del",{"id":id},function(data) {
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
