<?php
header('content-type:text/html;charset=utf-8');
?>
<div class="container">
    <div class="row">
        <h1>文章列表</h1>
    </div>
    <div class="row">
        <div>
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th>#</th>
                    <th>标题</th>
                    <th>文章说明</th>
                    <th>所属分类</th>
                    <th>排序</th>
                    <th>状态</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($articleList as $article):?>
                    <tr>
                        <td><?=$article->id?></td>
                        <td><?=$article->name?></td>
                        <td><?=$article->intro?></td>
                        <td><?=$article->category->name?></td>
                        <td><?=$article->sort?></td>
                        <td><?=$article->status==0?"隐藏":"显示"?></td>
                        <td><?=date("Y-m-d H:i:s",$article->create_time)?></td>
                        <td>
                            <?php if (Yii::$app->user->can('article/del')):?>
                            <a href="javascript:;" class="del btn btn-warning btn-xs">删除</a>
                            <?php endif;?>
                            <?php if (Yii::$app->user->can('article/upd')):?>
                            <a href="<?=\yii\helpers\Url::to(["/article/upd","id"=>$article->id])?>" class="btn btn-success btn-xs">编辑</a>
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
          $.post("/article/del",{"id":id},function(data) {
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
