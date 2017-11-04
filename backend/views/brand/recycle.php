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
                    <th>品牌Logo</th>
                    <th>排序</th>
                    <th>状态</th>
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
