<?php
header('content-type:text/html;charset=utf-8');
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($artmodel,"name")->textInput();
echo $form->field($artmodel,"intro")->textarea();
echo $form->field($artmodel,"article_category_id")->dropDownList(\backend\models\ArticleCategory::categoryOne());
echo $form->field($artmodel,"sort")->textInput();
echo $form->field($artmodel,"status",["inline"=>true])->radioList([0=>"隐藏",1=>"显示"]);
echo $form->field($artDetailModel,"content")->widget(\kucha\ueditor\UEditor::className(),[]);
echo \yii\bootstrap\Html::submitButton("提交",["class"=>"btn btn-info"]);
\yii\bootstrap\ActiveForm::end();