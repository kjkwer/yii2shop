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
                </tr>
                </thead>
                <tbody>
                <?php foreach ($articeCategoryList as $articeCategory):?>
                    <tr>
                        <td><?=$articeCategory->id?></td>
                        <td><?=$articeCategory->name?></td>
                        <td><?=$articeCategory->intro?></td>
                        <td><?=$articeCategory->sort?></td>
                        <td>删除</td>
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
