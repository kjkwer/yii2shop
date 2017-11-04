<?php
header('content-type:text/html;charset=utf-8');
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"name")->textInput();
echo $form->field($model,"intro")->textarea();
echo $form->field($model,"logoFile")->fileInput();
echo $form->field($model,"sort")->textInput();
echo $form->field($model,"status",["inline"=>true])->radioList([0=>"隐藏",1=>"显示"]);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();