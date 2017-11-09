<?php
header('content-type:text/html;charset=utf-8');
?>
<div>
    <h1>编辑角色</h1>
</div>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($roleForm,'name')->textInput();
echo $form->field($roleForm,'desc')->textInput();
echo $form->field($roleForm,'per',["inline"=>true])->checkboxList($permissions);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();