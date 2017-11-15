<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>用户注册</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/login.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
</head>
<body>
<!-- 顶部导航 start -->
<div class="topnav">
    <div class="topnav_bd w990 bc">
        <div class="topnav_left">

        </div>
        <div class="topnav_right fr">
            <ul>
                <li>您好，欢迎来到京西！
                    <?php if (Yii::$app->user->isGuest):?>
                        [<a href="/member/login">登录</a>] [<a href="/member/register">免费注册</a>]
                    <?php else:?>
                        [<?=Yii::$app->user->identity->username?>] [<a href="/member/logout">注销登录</a>]
                    <?php endif;?>
                </li>
                <li class="line">|</li>
                <li>我的订单</li>
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
    </div>
</div>
<!-- 页面头部 end -->

<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <form action="" id="myform" method="post">
                <input name="_csrf-frontend" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
                <ul>
                    <li>
                        <label for="">用户名：</label>
                        <input type="text" class="txt" id="username" name="username" />
                        <p>3-20位字符，可由中文、字母、数字和下划线组成</p>
                    </li>
                    <li>
                        <label for="">密码：</label>
                        <input type="password" class="txt" id="password_hash" name="password_hash" />
                        <p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>
                    </li>
                    <li>
                        <label for="">确认密码：</label>
                        <input type="password" class="txt" id="repassword" name="repassword" />
                        <p> <span>请再次输入密码</p>
                    </li>
                    <li>
                        <label for="">邮箱：</label>
                        <input type="text" class="txt" id="email" name="email" />
                        <p>邮箱必须合法</p>
                    </li>
                    <li>
                        <label for="">手机号码：</label>
                        <input type="text" class="txt" value="" name="tel" id="tel" placeholder=""/>
                    </li>
                    <li>
                        <label for="">短信验证码：</label>
                        <input type="text" class="txt" value="" placeholder="请输入短信验证码" name="tel_captcha" disabled="disabled" id="tel_captcha"/> <input type="button" onclick="bindPhoneNum(this)" name="get_captcha" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>

                    </li>
                    <li class="checkcode">
                        <label for="">验证码：</label>
                        <input type="text" name="captcha" /><br>
                        <img src="" id="captchaImage" alt="" />
                        <span>看不清？<a href="javascript:;" id="qiehuan">换一张</a></span>
                    </li>

                    <li>
                        <label for="">&nbsp;</label>
                        <input type="checkbox" class="chb" name="agree" checked="checked" /> 我已阅读并同意《用户注册协议》
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn" />
                    </li>
                </ul>
            </form>
        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->

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
<script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/lib/jquery.js"></script>
<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/jquery.validate.min.js"></script>
<script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/localization/messages_zh.js"></script>
<script type="text/javascript">
    function bindPhoneNum(){
        //>>获取电话,并验证电话格式
        var tel = $("#tel").val();
        var regexp = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        if (regexp.test(tel)){
            $.get("<?=\yii\helpers\Url::to(["/member/check-sms"])?>",{"tel":tel},function (data) {
                if (data==1){
                    alert("短信发送成功")
                }else {
                    alert("短信发送失败,请稍后再试")
                }
            })
        }
        //启用输入框
        $('#tel_captcha').prop('disabled',false);
        var time=30;
        var interval = setInterval(function(){
            time--;
            if(time<=0){
                clearInterval(interval);
                var html = '获取验证码';
                $('#get_captcha').prop('disabled',false);
            } else{
                var html = time + ' 秒后再次获取';
                $('#get_captcha').prop('disabled',true);
            }
            $('#get_captcha').val(html);
        },1000);
    }
    $().ready(function() {
//>>在键盘按下并释放及提交后验证提交表单
        $("#myform").validate({
            rules: {
                username: {
                    required: true,
                    minlength: 4,
                    //>>用户名唯一
                    remote: "<?=\yii\helpers\Url::to(["/member/check-username"])?>"
                },
                password_hash: {
                    required: true,
                    minlength: 5
                },
                repassword: {
                    required: true,
                    minlength: 5,
                    equalTo: "#password_hash"
                },
                email: {
                    required: true,
                    email: true,
                    //>>邮箱唯一
                    remote: "<?=\yii\helpers\Url::to(["/member/check-email"])?>"
                },
                tel: {
                    required: true,
                    checkTel: true,
                    //>>电话号码唯一
                    remote: "<?=\yii\helpers\Url::to(["/member/check-tel"])?>"
                },
                tel_captcha: {
                    remote: {
                        url: "<?=\yii\helpers\Url::to(["/member/check-telcaptcha"])?>",     //后台处理程序
                        type: "get",               //数据发送方式
                        dataType: "json",           //接受数据格式
                        data: {                     //要传递的数据
                            tel: function() {
                                return $("#tel").val();
                            },
                            tel_captcha: function () {
                                return $("#tel_captcha").val()
                            }
                        }
                    }
                },
                captcha: {
                    checkCaptcha: true
                },
                agree: "required"
            },
            messages: {
                username: {
                    required: "请输入用户名",
                    minlength: "用户名必需由两个字母组成",
                    remote: "用户名已存在"
                },
                password: {
                    required: "请输入密码",
                    minlength: "密码长度不能小于 5 个字母"
                },
                repassword: {
                    required: "请确认密码",
                    minlength: "密码长度不能小于 5 个字母",
                    equalTo: "两次密码输入不一致"
                },
                tel: {
                    required: "请输入电话",
                    remote: "该电话已被注册"
                },
                tel_captcha: {
                    remote: "验证码输入错误"
                },
                email: "请输入一个正确的邮箱",
                agree: "请接受我们的声明"
            },
            errorElement: "span"
        })
    });
//>>刷新验证码图片
    $("#qiehuan").click(function () {
        qiehuanCaptcha();
    });
    function qiehuanCaptcha() {
        $.getJSON("/site/captcha?refresh=1",function (data) {
            $("#captchaImage").attr("src",data.url);
            $("#qiehuan").attr("hash",data.hash1)
        })
    }
    qiehuanCaptcha();
//>>验证验证码
    jQuery.validator.addMethod("checkCaptcha", function(value, element) {
        var hash = $("#qiehuan").attr('hash');
        var val =  value.toLowerCase();
        var h = 0;
        for (var i = val.length - 1; i >= 0; --i) {
            h += val.charCodeAt(i);
        }
        return h == hash;
    }, "验证码不正确");
//>>自定义手机号验证规则
    jQuery.validator.addMethod("checkTel", function(value, element) {
        var tel = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
        return this.optional(element) || (tel.test(value));
    }, "请填写正确手机号码");
</script>
</body>
</html>