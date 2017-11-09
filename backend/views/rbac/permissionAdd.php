<?php
header('content-type:text/html;charset=utf-8');
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($permission,"route")->textInput();
echo $form->field($permission,"desc")->textInput();
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();