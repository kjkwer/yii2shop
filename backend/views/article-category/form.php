<?php
header('content-type:text/html;charset=utf-8');
?>
<div>
    <h1>编辑文章目录</h1>
</div>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"name")->textInput();
echo $form->field($model,"intro")->textarea();
echo $form->field($model,"sort")->textInput();
echo $form->field($model,"status",["inline"=>true])->radioList([0=>"隐藏",1=>"显示"]);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();