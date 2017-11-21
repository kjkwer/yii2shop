<?php
header('content-type:text/html;charset=utf-8');
?>
<div class="container">
    <div class="row">
        <h1>商品列表</h1>
    </div>
    <div class="row">
        <form action="/goods/list" method="get">
            <input type="text" name="name" value="<?=$name?>" placeholder="商品名称">
            <input type="text" name="sn" value="<?=$sn?>" placeholder="商品SN号">
            <input type="text" name="minPrice" value="<?=$minPrice?>" placeholder="最低价格">
            <input type="text"  name="maxPrice" value="<?=$maxPrice?>" placeholder="最高价格">
            <input type="submit" value="提交">
        </form>
    </div>
    <div class="row">
        <div>
            <table class="table table-striped table-condensed">
                <thead>
                <tr>
                    <th>#</th>
                    <th>货号</th>
                    <th>名称</th>
                    <th>价格</th>
                    <th>库存</th>
                    <th>上线状态</th>
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
                        <td><?=$goods->shop_price?></td>
                        <td><?=$goods->stock?></td>
                        <td><?=$goods->is_on_sale==1?"在售":"下架"?></td>
                        <td><?=\yii\bootstrap\Html::img($goods->logo,["width"=>"40px","height"=>"40px","class"=>"img-circle"])?></td>
                        <td>
                            <?php if (Yii::$app->user->can("goods/del")):?>
                            <?=\yii\bootstrap\Html::button("删除",["class"=>"del btn btn-warning btn-xs"])?>
                            <?php endif;?>
                            <?php if (Yii::$app->user->can("goods/upd")):?>
                            <?=\yii\bootstrap\Html::a("修改",\yii\helpers\Url::to(["/goods/upd","id"=>$goods->id]),["class"=>"btn btn-success btn-xs"])?>
                            <?php endif;?>
                            <?php if (Yii::$app->user->can("goods/preview")):?>
                            <?=\yii\bootstrap\Html::a("预览",\yii\helpers\Url::to(["/goods/preview","id"=>$goods->id]),["class"=>"btn btn-info btn-xs"])?>
                            <?php endif;?>
                            <?php if (Yii::$app->user->can("goods/images-list")):?>
                            <?=\yii\bootstrap\Html::a("图库",\yii\helpers\Url::to(["/goods/images-list","id"=>$goods->id]),["class"=>"btn btn-default btn-xs"])?>
                            <?=\yii\bootstrap\Html::button("静态化页面",["class"=>"static btn btn-info btn-xs"])?>
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
$url = \yii\helpers\Url::to("/goods/del");
$url1 = \yii\helpers\Url::to("/static/create-goods");
$this->registerJs(<<<JS
//>>给删除按钮添加点击事件
$(".del").click(function() {
    if (confirm("删除后数据无法恢复,是否继续?")){
        var id = $(this).closest("tr").find("td:first-child").text();
        var that = $(this);
        $.post("{$url}",{"id":id},function(data) {
            if (data==1){
                alert("删除成功");
                that.closest("tr").fadeOut();
            }else {
                alert(data);
            }
        })
    }
})
//>>页面静态化
$(".static").click(function() {
    var id = $(this).closest("tr").find("td:first-child").text();
    $.post("{$url1}",{"id":id},function(data) {
        if (data==1){
            alert("静态化成功");
        }
    })
})
JS
);