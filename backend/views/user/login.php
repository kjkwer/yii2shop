<?php
header('content-type:text/html;charset=utf-8');
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($loginModel,"username")->textInput();
echo $form->field($loginModel,"password_hash")->passwordInput();
echo $form->field($loginModel,"rem")->checkboxList([1=>"记住我"]);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-success"]);
\yii\bootstrap\ActiveForm::end();