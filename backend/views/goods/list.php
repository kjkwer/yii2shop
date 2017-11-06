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
                    <th>货号</th>
                    <th>名称</th>
                    <th>所属分类</th>
                    <th>品牌</th>
                    <th>价格</th>
                    <th>库存</th>
                    <th>Logo</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($goodsList as $goods):?>
                    <tr>
                        <td><?=$goods->id?></td>
                        <td><?=$goods->sn?></td>
                        <td><?=$goods->name?></td>
                        <td><?=$goods->category->name?></td>
                        <td><?=$goods->brand->name?></td>
                        <td><?=$goods->shop_price?></td>
                        <td><?=$goods->stock?></td>
                        <td><?=\yii\bootstrap\Html::img($goods->logo,["width"=>"40px","height"=>"40px","class"=>"img-circle"])?></td>
                        <td>
                            <?=\yii\bootstrap\Html::a("删除",["/goods/del"],["class"=>"del btn btn-warning btn-xs"])?>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2">
            <a href="/goods/add" class="btn btn-info btn-lg">添加</a>
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
