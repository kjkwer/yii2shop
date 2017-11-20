<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>填写核对订单信息</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/fillin.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">

    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/cart2.js"></script>

</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li id="userStatus">您好，欢迎来到京西！
                    [<a href="/member/login">登录</a>] [<a href="/member/register">免费注册</a>]
                </li>
                <li class="line">|</li>
                <li><a href="/order/list">我的订单</a></li>
                <li class="line">|</li>
                <li>客户服务</li>

            </ul>
        </div>
    </div>
</div>
<!-- 顶部导航 end -->

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="/index/index"><img src="/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<form action="/order/add" method="post">
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($addresses as $address):?>
                    <p><input type="radio" value="<?=$address->id?>" name="address_id" <?=$address->status==1?"checked":""?>/><?=$address->username?>&emsp;<?=$address->tel?>&emsp;<?=$address->provinces?>&emsp;<?=$address->cities?>&emsp;<?=$address->address?> </p>
                <?php endforeach;?>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>
            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="cur">
                        <td>
                            <input type="radio" name="delivery"" value="1" checked="checked" />普通快递送货上门
                        </td>
                        <td>￥10.00</td>
                        <td>每张订单不满499.00元,运费15.00元, 订单4...</td>
                    </tr>
                    <tr>

                        <td><input type="radio" name="delivery" value="2"/>特快专递</td>
                        <td>￥40.00</td>
                        <td>每张订单不满499.00元,运费40.00元, 订单4...</td>
                    </tr>
                    <tr>

                        <td><input type="radio" name="delivery"  value="3"/>加急快递送货上门</td>
                        <td>￥40.00</td>
                        <td>每张订单不满499.00元,运费40.00元, 订单4...</td>
                    </tr>
                    <tr>

                        <td><input type="radio" name="delivery" value="4"/>平邮</td>
                        <td>￥10.00</td>
                        <td>每张订单不满499.00元,运费15.00元, 订单4...</td>
                    </tr>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>
            <div class="pay_select">
                <table>
                    <tr class="cur">
                        <td class="col1"><input type="radio" name="pay" value="1" />货到付款</td>
                        <td class="col2">送货上门后再收款，支持现金、POS机刷卡、支票支付</td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="2" checked/>在线支付</td>
                        <td class="col2">即时到帐，支持绝大数银行借记卡及部分银行信用卡</td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="3"/>上门自提</td>
                        <td class="col2">自提时付款，支持现金、POS刷卡、支票支付</td>
                    </tr>
                    <tr>
                        <td class="col1"><input type="radio" name="pay" value="4"/>邮局汇款</td>
                        <td class="col2">通过快钱平台收款 汇款后1-3个工作日到账</td>
                    </tr>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>
            <div class="receipt_select ">
<!--                <form action="">-->
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
<!--                </form>-->
            </div>
        </div>
        <!-- 发票信息 end-->
        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($goodsList as $goods):?>
                    <tr>
                        <td class="col1"><a href="<?=\yii\helpers\Url::to(["/index/goods-intro","id"=>$goods->id])?>"><img src="<?=Yii::$app->params['adminImage'].$goods->logo?>" alt="" /></a>  <strong><a href="<?=\yii\helpers\Url::to(["/index/goods-intro","id"=>$goods->id])?>"><?=$goods->name?></a></strong></td>
                        <td class="col3">￥<span><?=$goods->shop_price?></span></td>
                        <td class="col4"> <?=$carts[$goods->id]; $totalAmount+=$carts[$goods->id]?></td>
                        <td class="col5"><span>￥<span><?php $price=$carts[$goods->id]*$goods->shop_price; $totalPrice+=$price; echo sprintf("%.2f",$price)?></span></span></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$totalAmount?> 件商品，总商品金额：</span>
                                <em>￥<?=sprintf("%.2f",$totalPrice)?></em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em >￥<span id="yf">10.00</span></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->
    </div>

    <div class="fillin_ft">
        <a href="javascript:;" id="refer"><span>提交订单</span></a>
        <p>应付总额：<strong id="total">￥<?=sprintf("%.2f",$totalPrice)+10.00?>元</strong></p>
    </div>
    </div>
</form>
<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="/images/xin.png" alt="" /></a>
        <a href=""><img src="/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="/images/police.jpg" alt="" /></a>
        <a href=""><img src="/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
<script type="text/javascript">
//>>提交表单,发送数据
$("#refer").click(function () {
    //>>获取收货地址id
    var address_id = $(".address").find("input:checked").val();
    if (!address_id){
        alert("请选择收货地址")
        return;
    }
    //>>获取送货方式
    var delivery = $(".delivery").find("input:checked").val();
    if (!delivery){
        alert("请选择配送方式")
        return;
    }
    //>>获取支付方式
    var pay = $(".pay").find("input:checked").val();
    if (!pay){
        alert("请选择支付方式")
        return;
    }
    //>>用Ajax方式
    $.post("/order/add",{"address_id":address_id,"delivery":delivery,"pay":pay},function (data) {
        if (data){
            alert("创建订单失败"+data);
        }else {
            window.location.href="/order/success";
        }
    })
})
//>>动态获取运费
$(".delivery input").click(function () {
    var text = $(this).val();
    var price;
    if (text == "1"){
        price = 10.00;
    }else if (text == "2"){
        price = 40.00;
    }else if (text == "3"){
        price = 40.00;
    }else if (text == "4"){
        price = 10.00;
    }
    $("#yf").text(price)
    $("#total").text(price+<?=$totalPrice?>)
})
/**
 * 通过Ajax获取用户的登录信息
 */
$.getJSON("/index/user-status",function (data) {
    if (data.login){
        $("#userStatus").html('[欢迎大佬<span style="color: red">'+data.username+'</span>光临本店] [<a href="/member/logout">注销登录</a>]')
    }
})
</script>
</body>
</html>
