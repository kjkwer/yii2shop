<?php
header('content-type:text/html;charset=utf-8');
?>
<div>
    <h1>编辑权限</h1>
</div>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($permissionForm,"route")->textInput();
echo $form->field($permissionForm,"desc")->textInput();
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();