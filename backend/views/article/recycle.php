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
                    <th>标题</th>
                    <th>文章说明</th>
                    <th>所属分类</th>
                    <th>排序</th>
                    <th>状态</th>
                    <th>创建时间</th>
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
                "pagination"=>$pager
            ])?>
        </div>
    </div>
</div>
