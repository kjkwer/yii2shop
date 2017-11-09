<?php
header('content-type:text/html;charset=utf-8');
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($rePwdForm,"oldPwd")->passwordInput();
echo $form->field($rePwdForm,"newPwd")->passwordInput();
echo $form->field($rePwdForm,"againNewPwd")->passwordInput();
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();