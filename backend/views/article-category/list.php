<?php
header('content-type:text/html;charset=utf-8');
?>
    <div class="container">
        <div class="row">
            <div>
                <table class="table table-striped table-condensed">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>品牌名称</th>
                        <th>品牌简介</th>
                        <th>排序</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($articeCategoryList as $articeCategory):?>
                        <tr>
                            <td><?=$articeCategory->id?></td>
                            <td><?=$articeCategory->name?></td>
                            <td><?=$articeCategory->intro?></td>
                            <td><?=$articeCategory->sort?></td>
                            <td><?=$articeCategory->status==0?"隐藏":"显示"?></td>
                            <td>
                                <a href="javascript:;" class="del btn btn-warning btn-xs">删除</a>
                                <a href="<?=\yii\helpers\Url::to(["/article-category/upd","id"=>$articeCategory->id])?>" class="btn btn-success btn-xs">更新</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-2">
                <a href="/article-category/add" class="btn btn-info btn-lg">添加</a>
            </div>
            <div class="col-lg-2">
                <a href="/article-category/recycle" class="btn btn-info btn-lg">回收站</a>
            </div>
            <div class="col-lg-6"></div>
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
          $.post("/article-category/del",{"id":id},function(data) {
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
